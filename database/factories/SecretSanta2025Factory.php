<?php

namespace Database\Factories;

use App\Models\SecretSanta2025;
use Illuminate\Database\Eloquent\Factories\Factory;

class SecretSanta2025Factory extends Factory
{
    protected $model = SecretSanta2025::class;

    public function definition()
    {
        return [
            'user_id' => null,
            'match_id' => null,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->streetAddress(),
            'message' => fake()->sentences(3, true),
        ];
    }
}
