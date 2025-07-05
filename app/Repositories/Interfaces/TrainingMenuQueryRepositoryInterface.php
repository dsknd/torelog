<?php

namespace App\Repositories\Interfaces;

use App\Models\TrainingMenu;
use Illuminate\Database\Eloquent\Collection;

interface TrainingMenuQueryRepositoryInterface
{
    public function findById(int $id): ?TrainingMenu;

    public function findByUserId(int $userId): Collection;

    public function findByUserIdWithExercises(int $userId): Collection;
}
