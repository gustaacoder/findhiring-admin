<?php

namespace App\DTO;

final readonly class ScreeningOutcome
{
    /** @param list<ScreeningCriterionOutcome> $criteria */
    public function __construct(
        public array $criteria,
        public string $summary,
    ) {}

    public function matchedCriterionIds(): array
    {
        return array_values(array_map(
            fn (ScreeningCriterionOutcome $outcome): int => $outcome->criterionId,
            array_filter($this->criteria, fn (ScreeningCriterionOutcome $outcome): bool => $outcome->matched),
        ));
    }

    public function outcomeFor(int $criterionId): ?ScreeningCriterionOutcome
    {
        foreach ($this->criteria as $outcome) {
            if ($outcome->criterionId === $criterionId) {
                return $outcome;
            }
        }

        return null;
    }
}
