<?php

namespace App\UseCases\TrainingRecord\CreateTrainingRecord;

use App\Dto\TrainingRecord\CreateTrainingRecordInput;
use App\Dto\TrainingRecord\CreateTrainingRecordOutput;

interface CreateTrainingRecordUseCaseInterface
{
    public function execute(CreateTrainingRecordInput $input): CreateTrainingRecordOutput;
}