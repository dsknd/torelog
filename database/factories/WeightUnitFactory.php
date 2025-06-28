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
            'name' => $this->faker->randomElement(['キログラム', 'ポンド', 'グラム']),
            'symbol' => $this->faker->randomElement(['kg', 'lb', 'g']),
            'conversion_rate' => $this->faker->randomFloat(4, 0.001, 1.0),
        ];
    }

    public function kilogram(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'キログラム',
            'symbol' => 'kg',
            'conversion_rate' => 1.0,
        ]);
    }

    public function pound(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'ポンド',
            'symbol' => 'lb',
            'conversion_rate' => 0.453592,
        ]);
    }

    public function gram(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'グラム',
            'symbol' => 'g',
            'conversion_rate' => 0.001,
        ]);
    }
}