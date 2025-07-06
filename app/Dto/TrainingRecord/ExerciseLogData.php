<?php

namespace App\Dto\TrainingRecord;

readonly class ExerciseLogData
{
    public function __construct(
        public int $exerciseId,
        public int $weightUnitId,
        public int $setNumber,
        public float $weight,
        public int $reps,
        public ?string $memo = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'exercise_id' => $this->exerciseId,
            'weight_unit_id' => $this->weightUnitId,
            'set_number' => $this->setNumber,
            'weight' => $this->weight,
            'reps' => $this->reps,
            'memo' => $this->memo,
        ];
    }
}