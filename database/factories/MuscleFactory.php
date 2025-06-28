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
            'name' => $this->faker->randomElement([
                '胸筋', '肩', '上腕二頭筋', '上腕三頭筋', '広背筋', 
                '大腿四頭筋', 'ハムストリング', '臀筋', 'ふくらはぎ',
                '腹筋', '腹斜筋', '脊柱起立筋'
            ]),
            'muscle_group_category_id' => MuscleGroupCategory::factory(),
        ];
    }

    public function forCategory(MuscleGroupCategory $category): static
    {
        return $this->state(fn (array $attributes) => [
            'muscle_group_category_id' => $category->id,
        ]);
    }

    public function chest(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => '胸筋',
        ]);
    }

    public function shoulders(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => '肩',
        ]);
    }

    public function quadriceps(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => '大腿四頭筋',
        ]);
    }
}