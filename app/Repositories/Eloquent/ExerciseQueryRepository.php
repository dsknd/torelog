<?php

namespace App\Repositories\Eloquent;

use App\Models\Exercise;
use App\Repositories\Interfaces\ExerciseQueryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ExerciseQueryRepository implements ExerciseQueryRepositoryInterface
{
    public function findById(int $id): ?Exercise
    {
        return Exercise::with([
            'muscles.muscleGroupCategory',
            'muscleGroupCategories'
        ])->find($id);
    }
    
    public function findAll(): Collection
    {
        return Exercise::with(['muscleGroupCategories'])
            ->orderBy('name')
            ->get();
    }
    
    public function findByMuscleGroupCategoryId(int $categoryId): Collection
    {
        return Exercise::whereHas('muscleGroupCategories', function ($query) use ($categoryId) {
            $query->where('muscle_group_category_id', $categoryId);
        })
        ->with(['muscleGroupCategories'])
        ->orderBy('name')
        ->get();
    }
    
    public function findByMuscleId(int $muscleId): Collection
    {
        return Exercise::whereHas('muscles', function ($query) use ($muscleId) {
            $query->where('muscle_id', $muscleId);
        })
        ->with(['muscles.muscleGroupCategory'])
        ->orderBy('name')
        ->get();
    }
    
    public function searchByName(string $name): Collection
    {
        return Exercise::where('name', 'ILIKE', "%{$name}%")
            ->with(['muscleGroupCategories'])
            ->orderBy('name')
            ->get();
    }
}