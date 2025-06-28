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
            'name' => $this->faker->randomElement(['上半身', '下半身', '体幹', '全身', '有酸素']),
            'description' => $this->faker->sentence(),
        ];
    }

    public function upperBody(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => '上半身',
            'description' => '上半身の筋肉群',
        ]);
    }

    public function lowerBody(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => '下半身',
            'description' => '下半身の筋肉群',
        ]);
    }

    public function core(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => '体幹',
            'description' => '体幹部の筋肉群',
        ]);
    }
}