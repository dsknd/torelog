<?php

namespace Database\Factories;

use App\Models\TrainingRecord;
use App\Models\User;
use App\Models\TrainingMenu;
use App\ValueObjects\TrainingDate;
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
            'date' => new TrainingDate($this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d')),
            'memo' => $this->faker->optional(0.7)->sentence(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function withTrainingMenu(TrainingMenu $trainingMenu): static
    {
        return $this->state(fn (array $attributes) => [
            'training_menu_id' => $trainingMenu->id,
            'user_id' => $trainingMenu->user_id,
        ]);
    }

    public function withoutTrainingMenu(): static
    {
        return $this->state(fn (array $attributes) => [
            'training_menu_id' => null,
        ]);
    }

    public function withDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => new TrainingDate($date),
        ]);
    }

    public function today(): static
    {
        return $this->withDate(now()->format('Y-m-d'));
    }

    public function yesterday(): static
    {
        return $this->withDate(now()->subDay()->format('Y-m-d'));
    }

    public function thisWeek(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => new TrainingDate(
                $this->faker->dateTimeBetween('monday this week', 'sunday this week')->format('Y-m-d')
            ),
        ]);
    }

    public function withMemo(string $memo): static
    {
        return $this->state(fn (array $attributes) => [
            'memo' => $memo,
        ]);
    }
}