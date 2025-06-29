<?php

namespace Database\Factories;

use App\Models\TrainingMenuExercise;
use App\Models\TrainingMenu;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingMenuExercise>
 */
class TrainingMenuExerciseFactory extends Factory
{
    protected $model = TrainingMenuExercise::class;

    public function definition(): array
    {
        return [
            'training_menu_id' => TrainingMenu::factory(),
            'exercise_id' => Exercise::factory(),
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}