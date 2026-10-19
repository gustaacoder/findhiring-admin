<?php

namespace App\Models;

use App\Enums\ScreeningStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperimentRun extends Model
{
    protected $fillable = [
        'experiment_id',
        'job_id',
        'application_id',
        'screening_analysis_id',
        'run_index',
        'status',
        'score',
        'eligible',
        'duration_ms',
        'model_name',
        'prompt_version',
        'criteria_snapshot',
        'input_fingerprint',
        'error_code',
    ];

    protected function casts(): array
    {
        return [
            'status' => ScreeningStatus::class,
            'run_index' => 'integer',
            'score' => 'integer',
            'eligible' => 'boolean',
            'duration_ms' => 'integer',
            'criteria_snapshot' => 'array',
        ];
    }

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(EvaluationExperiment::class, 'experiment_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function analysis(): BelongsTo
    {
        return $this->belongsTo(ScreeningAnalysis::class, 'screening_analysis_id');
    }
}
