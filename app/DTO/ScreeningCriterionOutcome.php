<?php

namespace App\DTO;

final readonly class ScreeningCriterionOutcome
{
    public function __construct(
        public int $criterionId,
        public bool $matched,
        public string $evidence,
        public string $reason,
    ) {}

    public function toArray(): array
    {
        return [
            'criterion_id' => $this->criterionId,
            'matched' => $this->matched,
            'evidence' => $this->evidence,
            'reason' => $this->reason,
        ];
    }
}
