<?php

namespace App\Exceptions;

use RuntimeException;

class ScreeningException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly string $errorCode = 'screening_error',
        public readonly bool $retryable = false,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public static function resumeExtractionFailed(string $reason): self
    {
        return new self(__('Resume text extraction failed: :reason', ['reason' => $reason]), 'extraction_failed', false);
    }

    public static function emptyResumeText(): self
    {
        return new self(__('The resume does not contain extractable text.'), 'extraction_empty', false);
    }

    public static function geminiTransportError(string $reason): self
    {
        return new self(__('Gemini request failed: :reason', ['reason' => $reason]), 'gemini_transport', true);
    }

    public static function geminiRateLimited(): self
    {
        return new self(__('Gemini API rate limit reached.'), 'gemini_rate_limited', true);
    }

    public static function geminiUnavailable(string $reason): self
    {
        return new self(__('Gemini API is unavailable: :reason', ['reason' => $reason]), 'gemini_unavailable', true);
    }

    public static function geminiEmptyResponse(): self
    {
        return new self(__('Gemini returned an empty candidate response.'), 'gemini_empty_response', true);
    }

    public static function geminiMalformedJson(string $reason): self
    {
        return new self(__('Gemini returned a malformed structured response: :reason', ['reason' => $reason]), 'gemini_malformed_json', true);
    }

    public static function invalidStructuredResponse(string $reason): self
    {
        return new self(__('Gemini structured response failed validation: :reason', ['reason' => $reason]), 'invalid_structured_response', true);
    }

    public static function configurationMissing(): self
    {
        return new self(__('Gemini API key is not configured.'), 'gemini_not_configured', false);
    }
}
