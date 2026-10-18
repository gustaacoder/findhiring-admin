<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Enums\ReviewStatus;
use App\Enums\ScreeningStatus;
use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'candidate_id',
        'resume_path',
        'original_filename',
        'resume_mime_type',
        'resume_size_bytes',
        'resume_fingerprint',
        'status',
        'review_status',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
            'review_status' => ReviewStatus::class,
            'resume_size_bytes' => 'integer',
            'submitted_at' => 'datetime',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function screeningAnalyses(): HasMany
    {
        return $this->hasMany(ScreeningAnalysis::class);
    }

    public function currentAnalysis(): HasOne
    {
        return $this->hasOne(ScreeningAnalysis::class)->where('is_current', true);
    }

    public function latestCompletedAnalysis(): HasOne
    {
        return $this->hasOne(ScreeningAnalysis::class)
            ->where('status', ScreeningStatus::Completed)
            ->latestOfMany();
    }

    public function humanReviews(): HasMany
    {
        return $this->hasMany(HumanReview::class);
    }

    public function latestReview(): HasOne
    {
        return $this->hasOne(HumanReview::class)->latestOfMany();
    }

    public function experimentRuns(): HasMany
    {
        return $this->hasMany(ExperimentRun::class);
    }

    public function markAsProcessing(): void
    {
        $this->forceFill(['status' => ApplicationStatus::Processing])->save();
    }

    public function markAsScreened(): void
    {
        $this->forceFill(['status' => ApplicationStatus::Screened])->save();
    }

    public function markAsFailed(): void
    {
        $this->forceFill(['status' => ApplicationStatus::Failed])->save();
    }

    public function markReviewAsCompleted(ReviewStatus $status): void
    {
        $this->forceFill(['review_status' => $status])->save();
    }
}
