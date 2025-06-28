<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\Muscle;
use App\Models\MuscleGroupCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
{
    protected $model = Exercise::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'ベンチプレス', 'スクワット', 'デッドリフト', '懸垂', 'ダンベルプレス',
                'ラットプルダウン', 'ショルダープレス', 'バイセップカール', 'レッグプレス',
                'プランク', 'クランチ', 'ルーマニアンデッドリフト', 'ラテラルレイズ'
            ]),
            'description' => $this->faker->sentence(),
        ];
    }

    public function withMuscles(array $muscles = [], bool $isPrimary = true): static
    {
        return $this->afterCreating(function (Exercise $exercise) use ($muscles, $isPrimary) {
            if (empty($muscles)) {
                $muscles = Muscle::factory(rand(1, 3))->create();
            }
            
            foreach ($muscles as $muscle) {
                $exercise->muscles()->attach($muscle->id, ['is_primary' => $isPrimary]);
            }
        });
    }

    public function withMuscleGroupCategories(array $categories = []): static
    {
        return $this->afterCreating(function (Exercise $exercise) use ($categories) {
            if (empty($categories)) {
                $categories = MuscleGroupCategory::factory(rand(1, 2))->create();
            }
            
            foreach ($categories as $category) {
                $exercise->muscleGroupCategories()->attach($category->id);
            }
        });
    }

    public function benchPress(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'ベンチプレス',
            'description' => 'バーベルを使った基本的な胸筋トレーニング',
        ]);
    }

    public function squat(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'スクワット',
            'description' => '下半身の王道トレーニング',
        ]);
    }

    public function deadlift(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'デッドリフト',
            'description' => '全身を使った代表的なコンパウンド種目',
        ]);
    }
}