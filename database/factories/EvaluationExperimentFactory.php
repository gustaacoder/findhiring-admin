<?php

namespace Database\Factories;

use App\Enums\ExperimentStatus;
use App\Models\EvaluationExperiment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EvaluationExperimentFactory extends Factory
{
    protected $model = EvaluationExperiment::class;

    public function definition(): array
    {
        $name = 'Experiment '.fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'description' => fake()->paragraph(),
            'dataset_identifier' => Str::slug($name),
            'criteria_snapshot' => null,
            'model_name' => 'gemini-3.8-flash',
            'prompt_version' => 'v1',
            'processing_config' => null,
            'status' => ExperimentStatus::Draft,
            'started_at' => null,
            'completed_at' => null,
        ];
    }
}
