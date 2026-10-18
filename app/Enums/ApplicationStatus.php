<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ApplicationStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Screened = 'screened';
    case Failed = 'failed';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Pending => 'Pending Screening',
            self::Processing => 'Processing',
            self::Screened => 'Screened',
            self::Failed => 'Screening Failed',
        };
    }

    public function getIcon(): Heroicon|string|null
    {
        return match ($this) {
            self::Pending => Heroicon::Clock,
            self::Processing => Heroicon::ArrowPath,
            self::Screened => Heroicon::CheckCircle,
            self::Failed => Heroicon::XCircle,
        };
    }

    public function getColor(): string|null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Processing => 'info',
            self::Screened => 'success',
            self::Failed => 'danger',
        };
    }
}
