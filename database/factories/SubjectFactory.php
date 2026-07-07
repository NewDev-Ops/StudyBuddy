<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\SubjectNormalizer;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();
        return [
            'user_id' => User::factory(),
            'name' => $name,
            'color_code' => fake()->hexColor(),
        ];
    }
}
