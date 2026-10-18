<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum JobStatus: string implements HasColor, HasIcon, HasLabel
{
    case Draft = 'draft';
    case Published = 'published';
    case Closed = 'closed';
    case Archived = 'archived';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::Closed => 'Closed',
            self::Archived => 'Archived',
        };
    }

    public function getIcon(): Heroicon|string|null
    {
        return match ($this) {
            self::Draft => Heroicon::PencilSquare,
            self::Published => Heroicon::GlobeAlt,
            self::Closed => Heroicon::LockClosed,
            self::Archived => Heroicon::ArchiveBox,
        };
    }

    public function getColor(): string|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'success',
            self::Closed => 'warning',
            self::Archived => 'danger',
        };
    }

    public function isAcceptingApplications(): bool
    {
        return $this === self::Published;
    }
}
