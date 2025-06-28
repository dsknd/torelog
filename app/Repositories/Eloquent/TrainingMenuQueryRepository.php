<?php

namespace App\Repositories\Eloquent;

use App\Models\TrainingMenu;
use App\Repositories\Interfaces\TrainingMenuQueryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TrainingMenuQueryRepository implements TrainingMenuQueryRepositoryInterface
{
    public function findById(int $id): ?TrainingMenu
    {
        return TrainingMenu::with([
            'user',
            'exercises.muscleGroupCategories'
        ])->find($id);
    }
    
    public function findByUserId(int $userId): Collection
    {
        return TrainingMenu::where('user_id', $userId)
            ->orderBy('name')
            ->get();
    }
    
    public function findByUserIdWithExercises(int $userId): Collection
    {
        return TrainingMenu::with([
            'exercises.muscleGroupCategories'
        ])
        ->where('user_id', $userId)
        ->orderBy('name')
        ->get();
    }
}