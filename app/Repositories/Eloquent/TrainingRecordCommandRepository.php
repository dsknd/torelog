<?php

namespace App\Repositories\Eloquent;

use App\Models\TrainingRecord;
use App\Repositories\Interfaces\TrainingRecordCommandRepositoryInterface;

class TrainingRecordCommandRepository implements TrainingRecordCommandRepositoryInterface
{
    public function create(array $data): TrainingRecord
    {
        return TrainingRecord::create($data);
    }
    
    public function update(int $id, array $data): TrainingRecord
    {
        $trainingRecord = TrainingRecord::findOrFail($id);
        $trainingRecord->update($data);
        
        return $trainingRecord->fresh();
    }
    
    public function delete(int $id): bool
    {
        $trainingRecord = TrainingRecord::findOrFail($id);
        
        return $trainingRecord->delete();
    }
}