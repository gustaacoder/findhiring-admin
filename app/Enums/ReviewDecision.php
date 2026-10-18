<?php

namespace App\Enums;

use Filament\Support\Enums\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ReviewDecision: string implements HasLabel
{
    case Proceed = 'proceed';
    case DoNotProceed = 'do_not_proceed';

    public function getLabel(): ?Htmlable|string
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

    public function getColor(): string|null
    {
        return match ($this) {
            self::Proceed => 'success',
            self::DoNotProceed => 'danger',
        };
    }
}
