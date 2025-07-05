<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\TrainingRecord;
use App\Models\WeightUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExerciseLog>
 */
class ExerciseLogFactory extends Factory
{
    protected $model = ExerciseLog::class;

    public function definition(): array
    {
        return [
            'training_record_id' => TrainingRecord::factory(),
            'exercise_id' => Exercise::factory(),
            'set_number' => fake()->numberBetween(1, 5),
            'weight' => fake()->randomFloat(1, 10, 200),
            'weight_unit_id' => WeightUnit::factory(),
            'reps' => fake()->numberBetween(1, 20),
            'memo' => fake()->optional(0.3)->sentence(),
        ];
    }
}
