<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferenceEvaluation extends Model
{
    protected $fillable = [
        'experiment_id',
        'application_id',
        'evaluator_id',
        'criterion_results',
        'score',
        'eligible',
        'notes',
        'is_consolidated',
    ];

    protected function casts(): array
    {
        return [
            'criterion_results' => 'array',
            'score' => 'integer',
            'eligible' => 'boolean',
            'is_consolidated' => 'boolean',
        ];
    }

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(EvaluationExperiment::class, 'experiment_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function matchedCriterionIds(): array
    {
        return collect($this->criterion_results)
            ->filter(fn (array $result): bool => (bool) ($result['matched'] ?? false))
            ->pluck('job_criterion_id')
            ->map(fn ($id): int => (int) $id)
            ->values()
            ->all();
    }
}
