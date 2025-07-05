<?php

namespace Database\Factories;

use App\Models\TrainingMenu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingMenu>
 */
class TrainingMenuFactory extends Factory
{
    protected $model = TrainingMenu::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
        ];
    }
}
