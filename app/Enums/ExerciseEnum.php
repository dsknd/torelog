<?php

namespace App\Enums;

use App\Enums\Concerns\HasBasicMethods;

enum ExerciseEnum: string
{
    use HasBasicMethods;
    case BENCH_PRESS = 'bench_press';
    case DUMBBELL_PRESS = 'dumbbell_press';
    case PUSH_UPS = 'push_ups';
    case PULL_UPS = 'pull_ups';
    case LAT_PULLDOWN = 'lat_pulldown';
    case BARBELL_ROW = 'barbell_row';
    case DEADLIFT = 'deadlift';
    case SQUAT = 'squat';
    case LEG_PRESS = 'leg_press';
    case LUNGES = 'lunges';
    case LEG_CURL = 'leg_curl';
    case CALF_RAISE = 'calf_raise';
    case OVERHEAD_PRESS = 'overhead_press';
    case DUMBBELL_SHOULDER_PRESS = 'dumbbell_shoulder_press';
    case LATERAL_RAISE = 'lateral_raise';
    case BICEP_CURL = 'bicep_curl';
    case TRICEP_EXTENSION = 'tricep_extension';
    case PLANK = 'plank';
    case CRUNCHES = 'crunches';

    public function getPrimaryMuscles(): array
    {
        return match ($this) {
            self::BENCH_PRESS, self::DUMBBELL_PRESS, self::PUSH_UPS => [MuscleEnum::PECTORALIS_MAJOR],
            self::PULL_UPS, self::LAT_PULLDOWN => [MuscleEnum::LATISSIMUS_DORSI],
            self::BARBELL_ROW => [MuscleEnum::LATISSIMUS_DORSI, MuscleEnum::RHOMBOIDS],
            self::DEADLIFT => [MuscleEnum::ERECTOR_SPINAE, MuscleEnum::GLUTES, MuscleEnum::HAMSTRINGS],
            self::SQUAT, self::LEG_PRESS => [MuscleEnum::QUADRICEPS, MuscleEnum::GLUTES],
            self::LUNGES => [MuscleEnum::QUADRICEPS, MuscleEnum::GLUTES],
            self::LEG_CURL => [MuscleEnum::HAMSTRINGS],
            self::CALF_RAISE => [MuscleEnum::CALVES],
            self::OVERHEAD_PRESS, self::DUMBBELL_SHOULDER_PRESS => [MuscleEnum::ANTERIOR_DELTOID],
            self::LATERAL_RAISE => [MuscleEnum::LATERAL_DELTOID],
            self::BICEP_CURL => [MuscleEnum::BICEPS_BRACHII],
            self::TRICEP_EXTENSION => [MuscleEnum::TRICEPS_BRACHII],
            self::PLANK => [MuscleEnum::RECTUS_ABDOMINIS, MuscleEnum::ERECTOR_SPINAE],
            self::CRUNCHES => [MuscleEnum::RECTUS_ABDOMINIS],
        };
    }

    public function getMuscleGroupCategories(): array
    {
        $primaryMuscles = $this->getPrimaryMuscles();
        $categories = [];

        foreach ($primaryMuscles as $muscle) {
            $category = $muscle->getMuscleGroupCategory();
            if (! in_array($category, $categories)) {
                $categories[] = $category;
            }
        }

        return $categories;
    }
}
