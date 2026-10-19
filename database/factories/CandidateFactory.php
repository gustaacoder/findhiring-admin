<?php

namespace Database\Factories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition(): array
    {
        $name = fake()->unique()->name();

        return [
            'name' => $name,
            'email' => Str::slug($name, '.').fake()->unique()->numberBetween(1, 9999).'@example.com',
            'phone' => fake()->optional(0.8)->numerify('+55 (##) #####-####'),
        ];
    }
}
