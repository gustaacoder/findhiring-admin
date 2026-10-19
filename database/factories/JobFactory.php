<?php

namespace Database\Factories;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        $title = ucwords(fake()->unique()->words(3, true)).' Developer';

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'description' => fake()->paragraphs(3, true),
            'status' => JobStatus::Draft,
            'published_at' => null,
            'closed_at' => null,
            'created_by' => User::factory(),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => JobStatus::Published,
            'published_at' => now()->subDay(),
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => JobStatus::Closed,
            'published_at' => now()->subMonth(),
            'closed_at' => now(),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => JobStatus::Archived,
            'published_at' => now()->subMonths(3),
            'closed_at' => now()->subMonths(2),
        ]);
    }
}
