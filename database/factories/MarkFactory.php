<?php

namespace Database\Factories;

use App\Models\Mark;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarkFactory extends Factory
{
    protected $model = Mark::class;

    public function definition(): array
    {
        $maxScore = fake()->randomElement([20, 30, 50, 100]);

        return [
            'subject_id' => Subject::factory(),
            'assessment_name' => fake()->words(3, true),
            'score' => fake()->numberBetween(0, $maxScore),
            'max_score' => $maxScore,
            'type' => fake()->randomElement(['Exam', 'Test', 'Quiz', 'Assignment']),
            'date' => fake()->date(),
        ];
    }
}
