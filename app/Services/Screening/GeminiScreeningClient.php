<?php

namespace App\Services\Screening;

use App\DTO\ScreeningOutcome;
use App\Exceptions\ScreeningException;
use Illuminate\Support\Facades\Http;

class GeminiScreeningClient
{
    private const ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct(
        private readonly ScreeningResponseValidator $validator,
        private readonly ScreeningPromptBuilder $promptBuilder,
    ) {}

    /**
     * @param  array<string, mixed>  $criteriaSnapshot
     */
    public function screen(array $criteriaSnapshot, string $resumeText): ScreeningOutcome
    {
        $apiKey = (string) config('findhiring.gemini.api_key');

        if ($apiKey === '') {
            throw ScreeningException::configurationMissing();
        }

        $expectedCriterionIds = array_values(array_map(
            fn (array $criterion): int => (int) $criterion['id'],
            $criteriaSnapshot['criteria'] ?? [],
        ));

        $response = Http::withHeaders(['x-goog-api-key' => $apiKey])
            ->acceptJson()
            ->timeout((int) config('findhiring.gemini.timeout', 90))
            ->connectTimeout(10)
            ->post($this->endpoint(), $this->requestPayload($criteriaSnapshot, $resumeText));

        if ($response->failed()) {
            throw $this->mapHttpFailure($response->status(), $response->body());
        }

        $json = $response->json();

        if (! is_array($json)) {
            throw ScreeningException::geminiMalformedJson('response body is not a JSON object.');
        }

        $text = $this->extractCandidateText($json);

        $decoded = json_decode($text, true);

        if (! is_array($decoded)) {
            throw ScreeningException::geminiMalformedJson('candidate content is not valid JSON.');
        }

        return $this->validator->validate($decoded, $expectedCriterionIds);
    }

    private function endpoint(): string
    {
        $model = rawurlencode((string) config('findhiring.gemini.model', 'gemini-3.8-flash'));

        return self::ENDPOINT.'/'.$model.':generateContent';
    }

    /**
     * @param  array<string, mixed>  $criteriaSnapshot
     * @return array<string, mixed>
     */
    private function requestPayload(array $criteriaSnapshot, string $resumeText): array
    {
        return [
            'systemInstruction' => [
                'parts' => [
                    ['text' => $this->promptBuilder->systemInstruction()],
                ],
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $this->promptBuilder->userMessage($criteriaSnapshot, $resumeText)],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => (float) config('findhiring.gemini.temperature', 0.1),
                'maxOutputTokens' => (int) config('findhiring.gemini.max_output_tokens', 8192),
                'responseMimeType' => 'application/json',
                'responseSchema' => $this->promptBuilder->responseSchema(),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $json
     */
    private function extractCandidateText(array $json): string
    {
        $candidates = $json['candidates'] ?? null;

        if (! is_array($candidates) || $candidates === []) {
            throw ScreeningException::geminiEmptyResponse();
        }

        $candidate = $candidates[0];
        $parts = $candidate['content']['parts'] ?? null;

        if (! is_array($parts) || $parts === []) {
            throw ScreeningException::geminiEmptyResponse();
        }

        $text = '';

        foreach ($parts as $part) {
            if (! is_array($part)) {
                continue;
            }

            if (($part['thought'] ?? false) === true) {
                continue;
            }

            if (isset($part['text']) && is_string($part['text'])) {
                $text .= $part['text'];
            }
        }

        $text = trim($text);

        if ($text === '') {
            throw ScreeningException::geminiEmptyResponse();
        }

        return $text;
    }

    private function mapHttpFailure(int $status, string $body): ScreeningException
    {
        $snippet = mb_substr(trim($body), 0, 300);

        return match (true) {
            $status === 429 => ScreeningException::geminiRateLimited(),
            $status >= 500 => ScreeningException::geminiUnavailable("HTTP {$status}: {$snippet}"),
            default => new ScreeningException(
                "Gemini API rejected the request (HTTP {$status}): {$snippet}",
                'gemini_rejected',
                false,
            ),
        };
    }
}
