<?php

namespace App\UseCases\Exercise\SearchExercise;

use App\Dto\Exercise\SearchExerciseInput;
use App\Dto\Exercise\SearchExerciseOutput;
use App\Repositories\Interfaces\ExerciseQueryRepositoryInterface;
use InvalidArgumentException;

class SearchExerciseUseCase implements SearchExerciseUseCaseInterface
{
    public function __construct(
        private ExerciseQueryRepositoryInterface $exerciseQueryRepository,
    ) {
    }

    public function execute(SearchExerciseInput $input): SearchExerciseOutput
    {
        $this->validateInput($input);

        $searchParams = $this->buildSearchParams($input);
        
        $result = $this->exerciseQueryRepository->search($searchParams);
        
        return new SearchExerciseOutput(
            exercises: $result['data'],
            pagination: $result['pagination']
        );
    }

    private function validateInput(SearchExerciseInput $input): void
    {
        if ($input->page < 1) {
            throw new InvalidArgumentException('Page number must be 1 or greater');
        }

        if ($input->perPage < 1 || $input->perPage > 100) {
            throw new InvalidArgumentException('Per page must be between 1 and 100');
        }

        if ($input->name !== null && strlen(trim($input->name)) < 1) {
            throw new InvalidArgumentException('Name must not be empty when provided');
        }

        if ($input->muscleGroupCategoryIds !== null && empty($input->muscleGroupCategoryIds)) {
            throw new InvalidArgumentException('Muscle group category IDs must not be empty when provided');
        }

        if ($input->muscleIds !== null && empty($input->muscleIds)) {
            throw new InvalidArgumentException('Muscle IDs must not be empty when provided');
        }
    }

    private function buildSearchParams(SearchExerciseInput $input): array
    {
        $params = [
            'page' => $input->page,
            'per_page' => $input->perPage,
        ];

        if ($input->name !== null) {
            $params['name'] = trim($input->name);
        }

        if ($input->muscleGroupCategoryIds !== null) {
            $params['muscle_group_category_ids'] = $input->muscleGroupCategoryIds;
        }

        if ($input->muscleIds !== null) {
            $params['muscle_ids'] = $input->muscleIds;
        }

        return $params;
    }
}