<?php

namespace App\Dto\Exercise;

readonly class SearchExerciseOutput
{
    /**
     * @param array $exercises
     * @param array $pagination
     */
    public function __construct(
        public array $exercises,
        public array $pagination,
    ) {
    }

    public function toArray(): array
    {
        return [
            'exercises' => $this->exercises,
            'pagination' => $this->pagination,
        ];
    }

    public function getExerciseCount(): int
    {
        return count($this->exercises);
    }
}