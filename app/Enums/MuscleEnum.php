<?php

namespace App\Enums;

use App\Enums\Concerns\HasBasicMethods;

enum MuscleEnum: string
{
    use HasBasicMethods;

    case PECTORALIS_MAJOR = 'pectoralis_major';
    case LATISSIMUS_DORSI = 'latissimus_dorsi';
    case TRAPEZIUS = 'trapezius';
    case RHOMBOIDS = 'rhomboids';
    case ERECTOR_SPINAE = 'erector_spinae';
    case QUADRICEPS = 'quadriceps';
    case HAMSTRINGS = 'hamstrings';
    case GLUTES = 'glutes';
    case CALVES = 'calves';
    case ANTERIOR_DELTOID = 'anterior_deltoid';
    case LATERAL_DELTOID = 'lateral_deltoid';
    case POSTERIOR_DELTOID = 'posterior_deltoid';
    case BICEPS_BRACHII = 'biceps_brachii';
    case TRICEPS_BRACHII = 'triceps_brachii';
    case FOREARM_FLEXORS = 'forearm_flexors';
    case FOREARM_EXTENSORS = 'forearm_extensors';
    case RECTUS_ABDOMINIS = 'rectus_abdominis';
    case OBLIQUES = 'obliques';

    public function getMuscleGroupCategory(): MuscleGroupCategoryEnum
    {
        return match ($this) {
            self::PECTORALIS_MAJOR => MuscleGroupCategoryEnum::CHEST,
            self::LATISSIMUS_DORSI, self::RHOMBOIDS, self::ERECTOR_SPINAE => MuscleGroupCategoryEnum::BACK,
            self::QUADRICEPS, self::HAMSTRINGS, self::GLUTES, self::CALVES => MuscleGroupCategoryEnum::LEGS,
            self::ANTERIOR_DELTOID, self::LATERAL_DELTOID, self::POSTERIOR_DELTOID,
            self::TRAPEZIUS => MuscleGroupCategoryEnum::SHOULDERS,
            self::BICEPS_BRACHII, self::TRICEPS_BRACHII,
            self::FOREARM_FLEXORS, self::FOREARM_EXTENSORS => MuscleGroupCategoryEnum::ARMS,
            self::RECTUS_ABDOMINIS, self::OBLIQUES => MuscleGroupCategoryEnum::ABS,
        };
    }

    public function getDescription(): string
    {
        return '';
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'value' => $this->value,
            'description' => $this->getDescription(),
            'muscle_group_category' => $this->getMuscleGroupCategory()->value,
        ];
    }

    public static function getByCategory(MuscleGroupCategoryEnum $category): array
    {
        return array_filter(
            self::cases(),
            fn ($muscle) => $muscle->getMuscleGroupCategory() === $category
        );
    }
}
