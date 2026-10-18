<?php

namespace App\Enums;

use Filament\Support\Enums\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ReviewStatus: string implements HasLabel
{
    case PendingReview = 'pending_review';
    case UnderReview = 'under_review';
    case Completed = 'completed';

    public function getLabel(): ?Htmlable|string
    {
        return match ($this) {
            self::PendingReview => 'Pending Review',
            self::UnderReview => 'Under Review',
            self::Completed => 'Review Completed',
        };
    }

    public function getIcon(): Heroicon|string|null
    {
        return match ($this) {
            self::PendingReview => Heroicon::ClipboardDocumentList,
            self::UnderReview => Heroicon::Eye,
            self::Completed => Heroicon::UserCheck,
        };
    }

    public function getColor(): string|null
    {
        return match ($this) {
            self::PendingReview => 'warning',
            self::UnderReview => 'info',
            self::Completed => 'success',
        };
    }
}
