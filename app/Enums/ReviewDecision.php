<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ReviewDecision: string implements HasColor, HasIcon, HasLabel
{
    case Proceed = 'proceed';
    case DoNotProceed = 'do_not_proceed';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Proceed => 'Proceed',
            self::DoNotProceed => 'Do Not Proceed',
        };
    }

    public function getIcon(): Heroicon|string|null
    {
        return match ($this) {
            self::Proceed => Heroicon::CheckCircle,
            self::DoNotProceed => Heroicon::XCircle,
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Proceed => 'success',
            self::DoNotProceed => 'danger',
        };
    }
}
