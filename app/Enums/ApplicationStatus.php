<?php

namespace App\Enums;

use Filament\Support\Enums\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ApplicationStatus: string implements HasLabel
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Screened = 'screened';
    case Failed = 'failed';

    public function getLabel(): ?Htmlable|string
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
