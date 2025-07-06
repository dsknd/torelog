<?php

namespace App\Dto\TrainingRecord;

readonly class CreateTrainingRecordOutput
{
    public function __construct(
        public int $trainingRecordId,
        public string $createdAt,
        public int $exerciseLogCount,
    ) {
    }

    public function toArray(): array
    {
        return [
            'training_record_id' => $this->trainingRecordId,
            'created_at' => $this->createdAt,
            'exercise_log_count' => $this->exerciseLogCount,
        ];
    }
}