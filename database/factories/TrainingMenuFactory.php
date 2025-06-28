<?php

namespace Database\Factories;

use App\Models\TrainingMenu;
use App\Models\User;
use App\Models\Exercise;
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
            'name' => $this->faker->randomElement([
                '胸・三頭筋', '背中・二頭筋', '肩・脚', '全身トレーニング',
                'プッシュデー', 'プルデー', 'レッグデー', '上半身', '下半身'
            ]),
            'description' => $this->faker->sentence(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function withExercises(array $exercises = []): static
    {
        return $this->afterCreating(function (TrainingMenu $trainingMenu) use ($exercises) {
            if (empty($exercises)) {
                $exercises = Exercise::factory(rand(3, 6))->create();
            }
            
            foreach ($exercises as $index => $exercise) {
                $trainingMenu->exercises()->attach($exercise->id, ['order' => $index + 1]);
            }
        });
    }

    public function pushDay(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'プッシュデー',
            'description' => '胸筋、肩、三頭筋を鍛えるメニュー',
        ]);
    }

    public function pullDay(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'プルデー',
            'description' => '背中、二頭筋を鍛えるメニュー',
        ]);
    }

    public function legDay(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'レッグデー',
            'description' => '下半身を集中的に鍛えるメニュー',
        ]);
    }
}