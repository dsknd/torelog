<?php

namespace Database\Factories;

use App\Models\ExerciseTargetMuscle;
use App\Models\Exercise;
use App\Models\Muscle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExerciseTargetMuscle>
 */
class ExerciseTargetMuscleFactory extends Factory
{
    protected $model = ExerciseTargetMuscle::class;

    public function definition(): array
    {
        return [
            'exercise_id' => Exercise::factory(),
            'muscle_id' => Muscle::factory(),
            'is_primary' => fake()->boolean(70),
        ];
    }
}