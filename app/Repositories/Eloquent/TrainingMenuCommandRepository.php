<?php

namespace App\Repositories\Eloquent;

use App\Models\TrainingMenu;
use App\Repositories\Interfaces\TrainingMenuCommandRepositoryInterface;

class TrainingMenuCommandRepository implements TrainingMenuCommandRepositoryInterface
{
    public function create(array $data): TrainingMenu
    {
        return TrainingMenu::create($data);
    }
    
    public function update(int $id, array $data): TrainingMenu
    {
        $trainingMenu = TrainingMenu::findOrFail($id);
        $trainingMenu->update($data);
        
        return $trainingMenu->fresh();
    }
    
    public function delete(int $id): bool
    {
        $trainingMenu = TrainingMenu::findOrFail($id);
        
        return $trainingMenu->delete();
    }
    
    public function attachExercises(int $menuId, array $exerciseData): void
    {
        $trainingMenu = TrainingMenu::findOrFail($menuId);
        
        foreach ($exerciseData as $exerciseId => $pivotData) {
            $trainingMenu->exercises()->attach($exerciseId, $pivotData);
        }
    }
    
    public function syncExercises(int $menuId, array $exerciseData): void
    {
        $trainingMenu = TrainingMenu::findOrFail($menuId);
        $trainingMenu->exercises()->sync($exerciseData);
    }
}