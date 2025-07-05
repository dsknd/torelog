<?php

namespace App\Repositories\Interfaces;

use App\Models\TrainingMenu;

interface TrainingMenuCommandRepositoryInterface
{
    public function create(array $data): TrainingMenu;

    public function update(int $id, array $data): TrainingMenu;

    public function delete(int $id): bool;

    public function attachExercises(int $menuId, array $exerciseData): void;

    public function syncExercises(int $menuId, array $exerciseData): void;
}
