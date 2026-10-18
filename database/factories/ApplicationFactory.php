<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Enums\ReviewStatus;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'candidate_id' => Candidate::factory(),
            'resume_path' => 'resumes/'.fake()->uuid().'.pdf',
            'original_filename' => fake()->word().'-resume.pdf',
            'resume_mime_type' => 'application/pdf',
            'resume_size_bytes' => fake()->numberBetween(20_000, 400_000),
            'resume_fingerprint' => hash('sha256', fake()->uuid()),
            'status' => ApplicationStatus::Pending,
            'review_status' => ReviewStatus::PendingReview,
            'submitted_at' => now(),
        ];
    }

    public function screened(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ApplicationStatus::Screened,
        ]);
    }
}
