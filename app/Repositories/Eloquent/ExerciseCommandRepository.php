<?php

namespace App\Repositories\Eloquent;

use App\Models\Exercise;
use App\Repositories\Interfaces\ExerciseCommandRepositoryInterface;

class ExerciseCommandRepository implements ExerciseCommandRepositoryInterface
{
    public function create(array $data): Exercise
    {
        return Exercise::create($data);
    }
    
    public function update(int $id, array $data): Exercise
    {
        $exercise = Exercise::findOrFail($id);
        $exercise->update($data);
        
        return $exercise->fresh();
    }
    
    public function delete(int $id): bool
    {
        $exercise = Exercise::findOrFail($id);
        
        return $exercise->delete();
    }
    
    public function attachMuscles(int $exerciseId, array $muscleData): void
    {
        $exercise = Exercise::findOrFail($exerciseId);
        
        foreach ($muscleData as $muscleId => $pivotData) {
            $exercise->muscles()->attach($muscleId, $pivotData);
        }
    }
    
    public function syncMuscles(int $exerciseId, array $muscleData): void
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $exercise->muscles()->sync($muscleData);
    }
    
    public function attachMuscleGroupCategories(int $exerciseId, array $categoryIds): void
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $exercise->muscleGroupCategories()->attach($categoryIds);
    }
    
    public function syncMuscleGroupCategories(int $exerciseId, array $categoryIds): void
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $exercise->muscleGroupCategories()->sync($categoryIds);
    }
}