<?php

namespace Database\Factories;

use App\Models\Muscle;
use App\Models\MuscleGroupCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Muscle>
 */
class MuscleFactory extends Factory
{
    protected $model = Muscle::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'muscle_group_category_id' => MuscleGroupCategory::factory(),
        ];
    }
}