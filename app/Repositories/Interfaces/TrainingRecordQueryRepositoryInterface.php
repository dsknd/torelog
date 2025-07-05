<?php

namespace App\Repositories\Interfaces;

use App\Models\TrainingRecord;
use Illuminate\Database\Eloquent\Collection;

interface TrainingRecordQueryRepositoryInterface
{
    public function findById(int $id): ?TrainingRecord;

    public function findByUserId(int $userId): Collection;

    public function findByUserIdAndDate(int $userId, string $date): Collection;

    public function findByUserIdWithDateRange(int $userId, string $startDate, string $endDate): Collection;
}
