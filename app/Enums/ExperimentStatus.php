<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ExperimentStatus: string implements HasColor, HasIcon, HasLabel
{
    case Draft = 'draft';
    case Ready = 'ready';
    case Running = 'running';
    case Completed = 'completed';
    case Failed = 'failed';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Draft => __('Draft'),
            self::Ready => __('Ready'),
            self::Running => __('Running'),
            self::Completed => __('Completed'),
            self::Failed => __('Failed'),
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

    public function getColor(): ?string
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
