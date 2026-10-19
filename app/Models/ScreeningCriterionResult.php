<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScreeningCriterionResult extends Model
{
    protected $fillable = [
        'screening_analysis_id',
        'job_criterion_id',
        'matched',
        'evidence',
        'reason',
        'awarded_weight',
    ];

    protected function casts(): array
    {
        return [
            'matched' => 'boolean',
            'awarded_weight' => 'integer',
        ];
    }

    public function analysis(): BelongsTo
    {
        return $this->belongsTo(ScreeningAnalysis::class, 'screening_analysis_id');
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(JobCriterion::class, 'job_criterion_id');
    }
}
