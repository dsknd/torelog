<?php

namespace Database\Factories;

use App\Models\TrainingMenu;
use App\Models\TrainingRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingRecord>
 */
class TrainingRecordFactory extends Factory
{
    protected $model = TrainingRecord::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'training_menu_id' => TrainingMenu::factory(),
            'date' => fake()->dateTimeBetween('-1 month', 'now'),
            'memo' => fake()->optional(0.7)->sentence(),
        ];
    }
}
