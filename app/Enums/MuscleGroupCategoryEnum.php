<?php

namespace App\Enums;

use App\Enums\Concerns\HasBasicMethods;

enum MuscleGroupCategoryEnum: string
{
    use HasBasicMethods;
    case CHEST = 'chest';
    case BACK = 'back';
    case LEGS = 'legs';
    case SHOULDERS = 'shoulders';
    case ARMS = 'arms';
    case ABS = 'abs';

}
