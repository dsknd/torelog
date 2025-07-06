<?php

namespace App\UseCases\Exercise\SearchExercise;

use App\Dto\Exercise\SearchExerciseInput;
use App\Dto\Exercise\SearchExerciseOutput;

interface SearchExerciseUseCaseInterface
{
    public function execute(SearchExerciseInput $input): SearchExerciseOutput;
}