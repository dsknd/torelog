<?php

namespace App\Repositories\Interfaces;

use App\Models\Exercise;

interface ExerciseCommandRepositoryInterface
{
    public function create(array $data): Exercise;
    
    public function update(int $id, array $data): Exercise;
    
    public function delete(int $id): bool;
    
    public function attachMuscles(int $exerciseId, array $muscleData): void;
    
    public function syncMuscles(int $exerciseId, array $muscleData): void;
    
    public function attachMuscleGroupCategories(int $exerciseId, array $categoryIds): void;
    
    public function syncMuscleGroupCategories(int $exerciseId, array $categoryIds): void;
}