<?php

namespace App\Models;

use App\Enums\CriterionType;
use App\Enums\JobStatus;
use Database\Factories\JobFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'published_at',
        'closed_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => JobStatus::class,
            'published_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(JobCriterion::class)->orderBy('sort_order')->orderBy('id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', JobStatus::Published);
    }

    public function isAcceptingApplications(): bool
    {
        return $this->status === JobStatus::Published;
    }

    public function totalCriteriaWeight(): int
    {
        return (int) $this->criteria->sum('weight');
    }

    public function hasBalancedCriteriaWeights(): bool
    {
        return $this->totalCriteriaWeight() === 100;
    }

    public function missingRequiredCriteria(): bool
    {
        return $this->criteria
            ->where('type', CriterionType::Required)
            ->isEmpty();
    }

    public function canBePublished(): bool
    {
        return $this->criteria->isNotEmpty()
            && ! $this->missingRequiredCriteria()
            && $this->hasBalancedCriteriaWeights();
    }

    public function publicUrl(): string
    {
        $base = rtrim((string) config('findhiring.frontend_url'), '/');

        return $base.'/jobs/'.$this->slug;
    }
}
