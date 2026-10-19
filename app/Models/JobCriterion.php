<?php

namespace App\Models;

use App\Enums\CriterionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobCriterion extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'name',
        'description',
        'expected_evidence',
        'type',
        'weight',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type' => CriterionType::class,
            'weight' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function isRequired(): bool
    {
        return $this->type === CriterionType::Required;
    }
}
