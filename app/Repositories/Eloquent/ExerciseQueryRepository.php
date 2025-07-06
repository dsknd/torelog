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
            'muscleGroupCategories',
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

    public function search(array $params): array
    {
        $query = Exercise::with(['muscleGroupCategories', 'muscles.muscleGroupCategory']);

        // 名前での検索
        if (isset($params['name'])) {
            $query->where('name', 'ILIKE', "%{$params['name']}%");
        }

        // 筋肉グループカテゴリでの検索
        if (isset($params['muscle_group_category_ids'])) {
            $query->whereHas('muscleGroupCategories', function ($q) use ($params) {
                $q->whereIn('muscle_group_category_id', $params['muscle_group_category_ids']);
            });
        }

        // 筋肉での検索
        if (isset($params['muscle_ids'])) {
            $query->whereHas('muscles', function ($q) use ($params) {
                $q->whereIn('muscle_id', $params['muscle_ids']);
            });
        }

        $query->orderBy('name');

        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 20;

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }
}
