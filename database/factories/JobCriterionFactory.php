<?php

namespace Database\Factories;

use App\Enums\CriterionType;
use App\Models\Job;
use App\Models\JobCriterion;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobCriterionFactory extends Factory
{
    protected $model = JobCriterion::class;

    public function definition(): array
    {
        $name = ucwords(fake()->unique()->words(3, true));

        return [
            'job_id' => Job::factory(),
            'name' => $name,
            'description' => fake()->sentence(10),
            'expected_evidence' => fake()->sentence(12),
            'type' => CriterionType::Required,
            'weight' => 20,
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    public function required(): static
    {
        return $this->state(fn (array $attributes): array => ['type' => CriterionType::Required]);
    }

    public function preferred(): static
    {
        return $this->state(fn (array $attributes): array => ['type' => CriterionType::Preferred]);
    }

    public function withWeight(int $weight): static
    {
        return $this->state(fn (array $attributes): array => ['weight' => $weight]);
    }
}
