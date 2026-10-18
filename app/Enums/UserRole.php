<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum UserRole: string implements HasLabel
{
    case Admin = 'admin';
    case Recruiter = 'recruiter';

    public function getLabel(): string|Htmlable|null
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
