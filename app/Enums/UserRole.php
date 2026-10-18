<?php

namespace App\Enums;

use Filament\Support\Enums\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum UserRole: string implements HasLabel
{
    case Admin = 'admin';
    case Recruiter = 'recruiter';

    public function getLabel(): ?Htmlable|string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Recruiter => 'Recruiter',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }
}
