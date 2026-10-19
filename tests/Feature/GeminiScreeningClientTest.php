<?php

namespace Tests\Feature;

use App\Services\Screening\GeminiScreeningClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiScreeningClientTest extends TestCase
{
    public function test_it_uses_a_fallback_model_when_the_primary_model_is_unavailable(): void
    {
        config()->set('findhiring.gemini.api_key', 'test-key');
        config()->set('findhiring.gemini.model', 'gemini-primary');
        config()->set('findhiring.gemini.fallback_models', ['gemini-fallback']);

        Http::fakeSequence()
            ->push([
                'error' => [
                    'code' => 503,
                    'message' => 'High demand.',
                    'status' => 'UNAVAILABLE',
                ],
            ], 503)
            ->push($this->successfulResponse(), 200);

        $outcome = app(GeminiScreeningClient::class)->screen($this->criteriaSnapshot(), 'Resume content with sufficient technical evidence.');

        $this->assertSame('gemini-fallback', $outcome->modelName);
        $this->assertSame([1], $outcome->matchedCriterionIds());

        Http::assertSentCount(2);
        Http::assertSent(fn (Request $request): bool => str_contains($request->url(), '/gemini-fallback:generateContent'));
    }

    private function criteriaSnapshot(): array
    {
        return [
            'job_id' => 1,
            'job_title' => 'Backend Developer',
            'criteria' => [
                [
                    'id' => 1,
                    'name' => 'Laravel',
                    'description' => 'Professional Laravel experience',
                    'expected_evidence' => 'Explicit Laravel experience',
                    'type' => 'required',
                    'weight' => 100,
                ],
            ],
        ];
    }

    private function successfulResponse(): array
    {
        return [
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            [
                                'text' => json_encode([
                                    'criteria' => [
                                        [
                                            'criterion_id' => 1,
                                            'matched' => true,
                                            'evidence' => 'Three years of Laravel experience.',
                                            'reason' => 'The résumé explicitly confirms Laravel experience.',
                                        ],
                                    ],
                                    'summary' => 'The required technical criterion was matched.',
                                ], JSON_THROW_ON_ERROR),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
