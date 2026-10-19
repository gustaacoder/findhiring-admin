<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ReviewStatus: string implements HasColor, HasIcon, HasLabel
{
    case PendingReview = 'pending_review';
    case UnderReview = 'under_review';
    case Completed = 'completed';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::PendingReview => __('Pending Review'),
            self::UnderReview => __('Under Review'),
            self::Completed => __('Review Completed'),
        };
    }

    public function getIcon(): Heroicon|string|null
    {
        return match ($this) {
            self::PendingReview => Heroicon::ClipboardDocumentList,
            self::UnderReview => Heroicon::Eye,
            self::Completed => Heroicon::ClipboardDocumentCheck,
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::PendingReview => 'warning',
            self::UnderReview => 'info',
            self::Completed => 'success',
        };
    }
}
