<?php

namespace App\Models;

use App\Enums\ScreeningStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScreeningAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'status',
        'score',
        'eligible',
        'summary',
        'model_name',
        'prompt_version',
        'criteria_snapshot',
        'duration_ms',
        'started_at',
        'completed_at',
        'error_code',
        'error_message',
        'attempts',
        'is_current',
        'input_fingerprint',
    ];

    protected function casts(): array
    {
        return [
            'status' => ScreeningStatus::class,
            'score' => 'integer',
            'eligible' => 'boolean',
            'criteria_snapshot' => 'array',
            'duration_ms' => 'integer',
            'attempts' => 'integer',
            'is_current' => 'boolean',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function criterionResults(): HasMany
    {
        return $this->hasMany(ScreeningCriterionResult::class);
    }

    public function experimentRuns(): HasMany
    {
        return $this->hasMany(ExperimentRun::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === ScreeningStatus::Completed;
    }

    public function isFailed(): bool
    {
        return $this->status === ScreeningStatus::Failed;
    }

    public function processingTimeInSeconds(): ?float
    {
        return $this->duration_ms === null ? null : round($this->duration_ms / 1000, 3);
    }
}
