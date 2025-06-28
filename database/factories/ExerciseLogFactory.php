<?php

namespace Database\Factories;

use App\Models\ExerciseLog;
use App\Models\TrainingRecord;
use App\Models\Exercise;
use App\Models\WeightUnit;
use App\ValueObjects\Weight;
use App\ValueObjects\Reps;
use App\ValueObjects\SetNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExerciseLog>
 */
class ExerciseLogFactory extends Factory
{
    protected $model = ExerciseLog::class;

    public function definition(): array
    {
        $weightUnit = WeightUnit::factory()->kilogram()->create();
        
        return [
            'training_record_id' => TrainingRecord::factory(),
            'exercise_id' => Exercise::factory(),
            'set_number' => new SetNumber($this->faker->numberBetween(1, 5)),
            'weight' => new Weight($this->faker->randomFloat(1, 10, 200), $weightUnit),
            'reps' => new Reps($this->faker->numberBetween(1, 20)),
            'memo' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    public function forTrainingRecord(TrainingRecord $trainingRecord): static
    {
        return $this->state(fn (array $attributes) => [
            'training_record_id' => $trainingRecord->id,
        ]);
    }

    public function forExercise(Exercise $exercise): static
    {
        return $this->state(fn (array $attributes) => [
            'exercise_id' => $exercise->id,
        ]);
    }

    public function withSetNumber(int $setNumber): static
    {
        return $this->state(fn (array $attributes) => [
            'set_number' => new SetNumber($setNumber),
        ]);
    }

    public function withWeight(float $value, WeightUnit $unit = null): static
    {
        if (!$unit) {
            $unit = WeightUnit::factory()->kilogram()->create();
        }
        
        return $this->state(fn (array $attributes) => [
            'weight' => new Weight($value, $unit),
        ]);
    }

    public function withReps(int $reps): static
    {
        return $this->state(fn (array $attributes) => [
            'reps' => new Reps($reps),
        ]);
    }

    public function heavySet(): static
    {
        $weightUnit = WeightUnit::factory()->kilogram()->create();
        
        return $this->state(fn (array $attributes) => [
            'weight' => new Weight($this->faker->randomFloat(1, 80, 150), $weightUnit),
            'reps' => new Reps($this->faker->numberBetween(1, 5)),
        ]);
    }

    public function lightSet(): static
    {
        $weightUnit = WeightUnit::factory()->kilogram()->create();
        
        return $this->state(fn (array $attributes) => [
            'weight' => new Weight($this->faker->randomFloat(1, 10, 50), $weightUnit),
            'reps' => new Reps($this->faker->numberBetween(8, 20)),
        ]);
    }

    public function bodyWeightSet(): static
    {
        $weightUnit = WeightUnit::factory()->kilogram()->create();
        
        return $this->state(fn (array $attributes) => [
            'weight' => new Weight(0, $weightUnit),
            'reps' => new Reps($this->faker->numberBetween(5, 30)),
        ]);
    }

    public function withMemo(string $memo): static
    {
        return $this->state(fn (array $attributes) => [
            'memo' => $memo,
        ]);
    }
}