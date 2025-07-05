<?php

namespace App\Repositories\Eloquent;

use App\Models\TrainingRecord;
use App\Repositories\Interfaces\TrainingRecordQueryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TrainingRecordQueryRepository implements TrainingRecordQueryRepositoryInterface
{
    public function findById(int $id): ?TrainingRecord
    {
        return TrainingRecord::with(['user', 'trainingMenu', 'exerciseLogs.exercise', 'exerciseLogs.weightUnit'])
            ->find($id);
    }

    public function findByUserId(int $userId): Collection
    {
        return TrainingRecord::with(['trainingMenu', 'exerciseLogs.exercise'])
            ->where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function findByUserIdAndDate(int $userId, string $date): Collection
    {
        return TrainingRecord::with(['trainingMenu', 'exerciseLogs.exercise', 'exerciseLogs.weightUnit'])
            ->where('user_id', $userId)
            ->whereDate('date', $date)
            ->get();
    }

    public function findByUserIdWithDateRange(int $userId, string $startDate, string $endDate): Collection
    {
        return TrainingRecord::with(['trainingMenu', 'exerciseLogs.exercise'])
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }
}
