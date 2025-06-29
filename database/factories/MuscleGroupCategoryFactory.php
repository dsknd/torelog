<?php

namespace Database\Factories;

use App\Models\MuscleGroupCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MuscleGroupCategory>
 */
class MuscleGroupCategoryFactory extends Factory
{
    protected $model = MuscleGroupCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
        ];
    }
}