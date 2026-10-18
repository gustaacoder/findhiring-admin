<?php

namespace App\Enums;

use Filament\Support\Enums\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ExperimentStatus: string implements HasLabel
{
    case Draft = 'draft';
    case Ready = 'ready';
    case Running = 'running';
    case Completed = 'completed';
    case Failed = 'failed';

    public function getLabel(): ?Htmlable|string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Ready => 'Ready',
            self::Running => 'Running',
            self::Completed => 'Completed',
            self::Failed => 'Failed',
        };
    }

    public function getIcon(): Heroicon|string|null
    {
        return match ($this) {
            self::Draft => Heroicon::PencilSquare,
            self::Ready => Heroicon::CheckBadge,
            self::Running => Heroicon::ArrowPath,
            self::Completed => Heroicon::Flag,
            self::Failed => Heroicon::ExclamationTriangle,
        };
    }

    public function getColor(): string|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Ready => 'info',
            self::Running => 'warning',
            self::Completed => 'success',
            self::Failed => 'danger',
        };
    }
}
