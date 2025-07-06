<?php

namespace App\Repositories\Interfaces;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Collection;

interface ExerciseQueryRepositoryInterface
{
    public function findById(int $id): ?Exercise;

    public function findAll(): Collection;

    public function findByMuscleGroupCategoryId(int $categoryId): Collection;

    public function findByMuscleId(int $muscleId): Collection;

    public function searchByName(string $name): Collection;

    public function search(array $params): array;
}
