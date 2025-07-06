<?php

namespace App\Dto\TrainingRecord;

readonly class CreateTrainingRecordInput
{
    /**
     * @param ExerciseLogData[] $exerciseLogs
     */
    public function __construct(
        public int $userId,
        public ?int $trainingMenuId,
        public string $date,
        public ?string $memo,
        public array $exerciseLogs,
    ) {
    }

    public function toTrainingRecordArray(): array
    {
        return [
            'user_id' => $this->userId,
            'training_menu_id' => $this->trainingMenuId,
            'date' => $this->date,
            'memo' => $this->memo,
        ];
    }

    /**
     * @return array<array>
     */
    public function toExerciseLogsArray(int $trainingRecordId): array
    {
        return array_map(
            fn(ExerciseLogData $exerciseLog) => array_merge(
                $exerciseLog->toArray(),
                ['training_record_id' => $trainingRecordId]
            ),
            $this->exerciseLogs
        );
    }

    public function getExerciseLogCount(): int
    {
        return count($this->exerciseLogs);
    }
}