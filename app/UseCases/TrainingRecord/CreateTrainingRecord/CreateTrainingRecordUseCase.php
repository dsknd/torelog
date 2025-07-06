<?php

namespace App\UseCases\TrainingRecord\CreateTrainingRecord;

use App\Dto\TrainingRecord\CreateTrainingRecordInput;
use App\Dto\TrainingRecord\CreateTrainingRecordOutput;
use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\User;
use App\Models\WeightUnit;
use App\Repositories\Interfaces\TrainingRecordCommandRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

class CreateTrainingRecordUseCase implements CreateTrainingRecordUseCaseInterface
{
    public function __construct(
        private TrainingRecordCommandRepositoryInterface $trainingRecordRepository,
    ) {
    }

    public function execute(CreateTrainingRecordInput $input): CreateTrainingRecordOutput
    {
        $this->validateInput($input);

        try {
            \DB::beginTransaction();

            // トレーニング記録を作成
            $trainingRecord = $this->trainingRecordRepository->create(
                $input->toTrainingRecordArray()
            );

            // エクササイズログを一括作成
            $exerciseLogsData = $input->toExerciseLogsArray($trainingRecord->id);
            foreach ($exerciseLogsData as $exerciseLogData) {
                ExerciseLog::create($exerciseLogData);
            }

            \DB::commit();

            return new CreateTrainingRecordOutput(
                trainingRecordId: $trainingRecord->id,
                createdAt: $trainingRecord->created_at->toISOString(),
                exerciseLogCount: $input->getExerciseLogCount(),
            );
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    private function validateInput(CreateTrainingRecordInput $input): void
    {
        // ユーザー存在確認
        if (!User::find($input->userId)) {
            throw new ModelNotFoundException("User with id {$input->userId} not found");
        }

        // エクササイズログが空でないことを確認
        if (empty($input->exerciseLogs)) {
            throw new InvalidArgumentException('Exercise logs cannot be empty');
        }

        // 各エクササイズログのバリデーション
        $exerciseIds = [];
        $weightUnitIds = [];
        foreach ($input->exerciseLogs as $exerciseLog) {
            $exerciseIds[] = $exerciseLog->exerciseId;
            $weightUnitIds[] = $exerciseLog->weightUnitId;

            // 値の範囲チェック
            if ($exerciseLog->setNumber < 1) {
                throw new InvalidArgumentException('Set number must be 1 or greater');
            }
            if ($exerciseLog->weight < 0) {
                throw new InvalidArgumentException('Weight must be 0 or greater');
            }
            if ($exerciseLog->reps < 1) {
                throw new InvalidArgumentException('Reps must be 1 or greater');
            }
        }

        // エクササイズ存在確認
        $existingExerciseIds = Exercise::whereIn('id', array_unique($exerciseIds))->pluck('id')->toArray();
        $missingExerciseIds = array_diff(array_unique($exerciseIds), $existingExerciseIds);
        if (!empty($missingExerciseIds)) {
            throw new ModelNotFoundException('Exercises not found: ' . implode(', ', $missingExerciseIds));
        }

        // 重量単位存在確認
        $existingWeightUnitIds = WeightUnit::whereIn('id', array_unique($weightUnitIds))->pluck('id')->toArray();
        $missingWeightUnitIds = array_diff(array_unique($weightUnitIds), $existingWeightUnitIds);
        if (!empty($missingWeightUnitIds)) {
            throw new ModelNotFoundException('Weight units not found: ' . implode(', ', $missingWeightUnitIds));
        }

        // 日付形式チェック（簡易）
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input->date)) {
            throw new InvalidArgumentException('Date must be in Y-m-d format');
        }
    }
}