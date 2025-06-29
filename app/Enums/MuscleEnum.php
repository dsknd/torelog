<?php

namespace App\Enums;

enum MuscleEnum: string
{
    // 胸
    case PECTORALIS_MAJOR = 'pectoralis_major';
    case PECTORALIS_MINOR = 'pectoralis_minor';

    // 背中
    case LATISSIMUS_DORSI = 'latissimus_dorsi';
    case TRAPEZIUS = 'trapezius';
    case RHOMBOIDS = 'rhomboids';
    case ERECTOR_SPINAE = 'erector_spinae';

    // 脚
    case QUADRICEPS = 'quadriceps';
    case HAMSTRINGS = 'hamstrings';
    case GLUTES = 'glutes';
    case CALVES = 'calves';

    // 肩
    case ANTERIOR_DELTOID = 'anterior_deltoid';
    case LATERAL_DELTOID = 'lateral_deltoid';
    case POSTERIOR_DELTOID = 'posterior_deltoid';

    // 腹筋
    case RECTUS_ABDOMINIS = 'rectus_abdominis';
    case OBLIQUES = 'obliques';
    case TRANSVERSE_ABDOMINIS = 'transverse_abdominis';

    public function getName(): string
    {
        return match($this) {
            self::PECTORALIS_MAJOR => 'Pectoralis Major',
            self::PECTORALIS_MINOR => 'Pectoralis Minor',
            self::LATISSIMUS_DORSI => 'Latissimus Dorsi',
            self::TRAPEZIUS => 'Trapezius',
            self::RHOMBOIDS => 'Rhomboids',
            self::ERECTOR_SPINAE => 'Erector Spinae',
            self::QUADRICEPS => 'Quadriceps',
            self::HAMSTRINGS => 'Hamstrings',
            self::GLUTES => 'Glutes',
            self::CALVES => 'Calves',
            self::ANTERIOR_DELTOID => 'Anterior Deltoid',
            self::LATERAL_DELTOID => 'Lateral Deltoid',
            self::POSTERIOR_DELTOID => 'Posterior Deltoid',
            self::RECTUS_ABDOMINIS => 'Rectus Abdominis',
            self::OBLIQUES => 'Obliques',
            self::TRANSVERSE_ABDOMINIS => 'Transverse Abdominis',
        };
    }

    public function getNameJa(): string
    {
        return match($this) {
            self::PECTORALIS_MAJOR => '大胸筋',
            self::PECTORALIS_MINOR => '小胸筋',
            self::LATISSIMUS_DORSI => '広背筋',
            self::TRAPEZIUS => '僧帽筋',
            self::RHOMBOIDS => '菱形筋',
            self::ERECTOR_SPINAE => '脊柱起立筋',
            self::QUADRICEPS => '大腿四頭筋',
            self::HAMSTRINGS => 'ハムストリング',
            self::GLUTES => '臀筋',
            self::CALVES => 'ふくらはぎ',
            self::ANTERIOR_DELTOID => '三角筋前部',
            self::LATERAL_DELTOID => '三角筋中部',
            self::POSTERIOR_DELTOID => '三角筋後部',
            self::RECTUS_ABDOMINIS => '腹直筋',
            self::OBLIQUES => '腹斜筋',
            self::TRANSVERSE_ABDOMINIS => '腹横筋',
        };
    }

    public function getMuscleGroupCategory(): MuscleGroupCategoryEnum
    {
        return match($this) {
            self::PECTORALIS_MAJOR, self::PECTORALIS_MINOR => MuscleGroupCategoryEnum::CHEST,
            self::LATISSIMUS_DORSI, self::TRAPEZIUS, self::RHOMBOIDS, self::ERECTOR_SPINAE => MuscleGroupCategoryEnum::BACK,
            self::QUADRICEPS, self::HAMSTRINGS, self::GLUTES, self::CALVES => MuscleGroupCategoryEnum::LEGS,
            self::ANTERIOR_DELTOID, self::LATERAL_DELTOID, self::POSTERIOR_DELTOID => MuscleGroupCategoryEnum::SHOULDERS,
            self::RECTUS_ABDOMINIS, self::OBLIQUES, self::TRANSVERSE_ABDOMINIS => MuscleGroupCategoryEnum::ABS,
        };
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'name_ja' => $this->getNameJa(),
            'muscle_group_category' => $this->getMuscleGroupCategory()->value,
        ];
    }

    public static function getByCategory(MuscleGroupCategoryEnum $category): array
    {
        return array_filter(
            self::cases(),
            fn($muscle) => $muscle->getMuscleGroupCategory() === $category
        );
    }
}