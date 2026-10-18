<?php

namespace App\Models;

use App\Enums\ExperimentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationExperiment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'dataset_identifier',
        'criteria_snapshot',
        'model_name',
        'prompt_version',
        'processing_config',
        'status',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ExperimentStatus::class,
            'criteria_snapshot' => 'array',
            'processing_config' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function runs(): HasMany
    {
        return $this->hasMany(ExperimentRun::class);
    }

    public function referenceEvaluations(): HasMany
    {
        return $this->hasMany(ReferenceEvaluation::class);
    }

    public function consolidatedReferences()
    {
        return $this->referenceEvaluations()->where('is_consolidated', true);
    }

    public function isReadyToRun(): bool
    {
        return in_array($this->status, [ExperimentStatus::Draft, ExperimentStatus::Ready, ExperimentStatus::Completed], true);
    }
}
