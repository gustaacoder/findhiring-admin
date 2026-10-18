<?php

namespace Database\Factories;

use App\Enums\ReviewDecision;
use App\Models\Application;
use App\Models\HumanReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HumanReviewFactory extends Factory
{
    protected $model = HumanReview::class;

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'reviewer_id' => User::factory(),
            'decision' => ReviewDecision::Proceed,
            'notes' => fake()->optional()->sentence(10),
            'reviewed_at' => now(),
        ];
    }
}
