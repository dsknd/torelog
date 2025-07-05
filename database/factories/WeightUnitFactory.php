<?php

namespace Database\Factories;

use App\Models\WeightUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeightUnit>
 */
class WeightUnitFactory extends Factory
{
    protected $model = WeightUnit::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'symbol' => fake()->lexify('??'),
            'conversion_rate' => fake()->randomFloat(4, 0.001, 1.0),
        ];
    }
}
