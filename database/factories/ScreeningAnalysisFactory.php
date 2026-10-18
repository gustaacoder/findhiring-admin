<?php

namespace Database\Factories;

use App\Enums\ScreeningStatus;
use App\Models\Application;
use App\Models\ScreeningAnalysis;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScreeningAnalysisFactory extends Factory
{
    protected $model = ScreeningAnalysis::class;

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'status' => ScreeningStatus::Queued,
            'score' => null,
            'eligible' => null,
            'summary' => null,
            'model_name' => 'gemini-3.8-flash',
            'prompt_version' => 'v1',
            'criteria_snapshot' => null,
            'duration_ms' => null,
            'started_at' => null,
            'completed_at' => null,
            'error_code' => null,
            'error_message' => null,
            'attempts' => 0,
            'is_current' => false,
            'input_fingerprint' => null,
        ];
    }

    public function completed(int $score, bool $eligible): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ScreeningStatus::Completed,
            'score' => $score,
            'eligible' => $eligible,
            'summary' => 'Automated technical screening completed.',
            'duration_ms' => fake()->numberBetween(1200, 9000),
            'started_at' => now()->subSeconds(10),
            'completed_at' => now(),
            'is_current' => true,
        ]);
    }

    public function failed(string $errorCode = 'gemini_error'): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ScreeningStatus::Failed,
            'error_code' => $errorCode,
            'error_message' => 'Screening could not be completed.',
            'completed_at' => now(),
        ]);
    }
}
