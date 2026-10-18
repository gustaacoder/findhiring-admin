<?php

namespace App\Models;

use App\Enums\UserRole;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements HasName
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function authoredJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'created_by');
    }

    public function humanReviews(): HasMany
    {
        return $this->hasMany(HumanReview::class, 'reviewer_id');
    }

    public function referenceEvaluations(): HasMany
    {
        return $this->hasMany(ReferenceEvaluation::class, 'evaluator_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
