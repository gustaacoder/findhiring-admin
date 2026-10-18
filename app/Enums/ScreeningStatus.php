<?php

namespace App\Enums;

use Filament\Support\Enums\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ScreeningStatus: string implements HasLabel
{
    case Queued = 'queued';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';

    public function getLabel(): ?Htmlable|string
    {
        return match ($this) {
            self::Queued => 'Queued',
            self::Processing => 'Processing',
            self::Completed => 'Completed',
            self::Failed => 'Failed',
        };
    }

    public function getIcon(): Heroicon|string|null
    {
        return match ($this) {
            self::Queued => Heroicon::Clock,
            self::Processing => Heroicon::ArrowPath,
            self::Completed => Heroicon::CheckCircle,
            self::Failed => Heroicon::ExclamationTriangle,
        };
    }

    public function getColor(): string|null
    {
        return match ($this) {
            self::Queued => 'gray',
            self::Processing => 'info',
            self::Completed => 'success',
            self::Failed => 'danger',
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Completed, self::Failed], true);
    }
}
