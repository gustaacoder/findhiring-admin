<?php

namespace App\DTO;

final readonly class ScoreResult
{
    /** @param array<int, int> $awardedWeights */
    public function __construct(
        public int $score,
        public bool $eligible,
        public array $awardedWeights,
    ) {}

    public function awardedWeightFor(int $criterionId): int
    {
        return $this->awardedWeights[$criterionId] ?? 0;
    }
}
