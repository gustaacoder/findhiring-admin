<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CriterionType: string implements HasLabel
{
    case Required = 'required';
    case Preferred = 'preferred';

    public function getLabel(): string|Htmlable|null
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
