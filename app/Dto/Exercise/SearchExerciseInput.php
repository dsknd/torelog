<?php

namespace App\Dto\Exercise;

readonly class SearchExerciseInput
{
    public function __construct(
        public ?string $name = null,
        public ?array $muscleGroupCategoryIds = null,
        public ?array $muscleIds = null,
        public int $page = 1,
        public int $perPage = 20,
    ) {
    }

    public function hasFilters(): bool
    {
        return $this->name !== null || 
               !empty($this->muscleGroupCategoryIds) || 
               !empty($this->muscleIds);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'muscle_group_category_ids' => $this->muscleGroupCategoryIds,
            'muscle_ids' => $this->muscleIds,
            'page' => $this->page,
            'per_page' => $this->perPage,
        ];
    }
}