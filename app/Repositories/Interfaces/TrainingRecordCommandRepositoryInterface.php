<?php

namespace App\Repositories\Interfaces;

use App\Models\TrainingRecord;

interface TrainingRecordCommandRepositoryInterface
{
    public function create(array $data): TrainingRecord;

    public function update(int $id, array $data): TrainingRecord;

    public function delete(int $id): bool;
}
