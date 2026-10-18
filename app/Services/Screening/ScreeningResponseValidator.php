<?php

namespace App\Services\Screening;

use App\DTO\ScreeningCriterionOutcome;
use App\DTO\ScreeningOutcome;
use App\Exceptions\ScreeningException;

class ScreeningResponseValidator
{
    private const MAX_EVIDENCE_LENGTH = 1500;

    private const MAX_REASON_LENGTH = 1500;

    private const MAX_SUMMARY_LENGTH = 3000;

    /**
     * @param  array<string, mixed>  $payload
     * @param  list<int>  $expectedCriterionIds
     */
    public function validate(array $payload, array $expectedCriterionIds): ScreeningOutcome
    {
        if (! array_key_exists('criteria', $payload) || ! is_array($payload['criteria'])) {
            throw ScreeningException::invalidStructuredResponse('missing criteria collection.');
        }

        if (! array_key_exists('summary', $payload) || ! is_string($payload['summary'])) {
            throw ScreeningException::invalidStructuredResponse('missing summary text.');
        }

        $summary = trim($payload['summary']);

        if ($summary === '' || mb_strlen($summary) > self::MAX_SUMMARY_LENGTH) {
            throw ScreeningException::invalidStructuredResponse('summary is empty or too long.');
        }

        $expected = array_values(array_unique(array_map('intval', $expectedCriterionIds)));

        if ($expected === []) {
            throw ScreeningException::invalidStructuredResponse('no expected criteria were provided.');
        }

        $outcomes = [];
        $seenIds = [];

        foreach ($payload['criteria'] as $index => $entry) {
            if (! is_array($entry)) {
                throw ScreeningException::invalidStructuredResponse("criteria entry {$index} is not an object.");
            }

            $criterionId = $entry['criterion_id'] ?? null;

            if (! is_int($criterionId) && ! (is_string($criterionId) && ctype_digit($criterionId))) {
                throw ScreeningException::invalidStructuredResponse("criteria entry {$index} has an invalid criterion_id.");
            }

            $criterionId = (int) $criterionId;

            if (! in_array($criterionId, $expected, true)) {
                throw ScreeningException::invalidStructuredResponse("unknown criterion_id {$criterionId}.");
            }

            if (isset($seenIds[$criterionId])) {
                throw ScreeningException::invalidStructuredResponse("duplicated criterion_id {$criterionId}.");
            }

            $seenIds[$criterionId] = true;

            $matched = $entry['matched'] ?? null;

            if (! is_bool($matched)) {
                throw ScreeningException::invalidStructuredResponse("criterion_id {$criterionId} has a non-boolean matched value.");
            }

            $evidence = $this->normalizeText($entry['evidence'] ?? null, self::MAX_EVIDENCE_LENGTH, "criterion_id {$criterionId} evidence");
            $reason = $this->normalizeText($entry['reason'] ?? null, self::MAX_REASON_LENGTH, "criterion_id {$criterionId} reason");

            $outcomes[] = new ScreeningCriterionOutcome($criterionId, $matched, $evidence, $reason);
        }

        $missing = array_diff($expected, array_keys($seenIds));

        if ($missing !== []) {
            throw ScreeningException::invalidStructuredResponse(
                'missing results for criterion ids: '.implode(', ', $missing).'.',
            );
        }

        usort($outcomes, fn (ScreeningCriterionOutcome $a, ScreeningCriterionOutcome $b): int => $a->criterionId <=> $b->criterionId);

        return new ScreeningOutcome($outcomes, $summary);
    }

    private function normalizeText(mixed $value, int $maxLength, string $field): string
    {
        if (! is_string($value)) {
            throw ScreeningException::invalidStructuredResponse("{$field} is not text.");
        }

        $value = trim(preg_replace('/\s+/u', ' ', $value) ?? $value);

        if ($value === '') {
            throw ScreeningException::invalidStructuredResponse("{$field} is empty.");
        }

        if (mb_strlen($value) > $maxLength) {
            $value = mb_substr($value, 0, $maxLength);
        }

        return $value;
    }
}
