<?php

namespace App\Enums;

use Filament\Support\Enums\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CriterionType: string implements HasLabel
{
    case Required = 'required';
    case Preferred = 'preferred';

    public function getLabel(): ?Htmlable|string
    {
        return match ($this) {
            self::Required => 'Required',
            self::Preferred => 'Preferred',
        };
    }

    public function isMandatory(): bool
    {
        return $this === self::Required;
    }
}
