<?php

namespace App\Enums;

enum MuscleEnum: string
{
    // 胸
    case PECTORALIS_MAJOR = 'pectoralis_major';

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

    // 腕
    case BICEPS_BRACHII = 'biceps_brachii';
    case TRICEPS_BRACHII = 'triceps_brachii';
    case FOREARM_FLEXORS = 'forearm_flexors';
    case FOREARM_EXTENSORS = 'forearm_extensors';

    // 腹筋
    case RECTUS_ABDOMINIS = 'rectus_abdominis';
    case OBLIQUES = 'obliques';

    public function getName(): string
    {
        return match ($this) {
            self::PECTORALIS_MAJOR => 'Pectoralis Major',
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
            self::BICEPS_BRACHII => 'Biceps Brachii',
            self::TRICEPS_BRACHII => 'Triceps Brachii',
            self::FOREARM_FLEXORS => 'Forearm Flexors',
            self::FOREARM_EXTENSORS => 'Forearm Extensors',
            self::RECTUS_ABDOMINIS => 'Rectus Abdominis',
            self::OBLIQUES => 'Obliques',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::PECTORALIS_MAJOR => '大胸筋 - 胸部の主要な筋肉',
            self::LATISSIMUS_DORSI => '広背筋 - 背中の広い筋肉',
            self::TRAPEZIUS => '僧帽筋 - 首と肩の筋肉',
            self::RHOMBOIDS => '菱形筋 - 肩甲骨の筋肉',
            self::ERECTOR_SPINAE => '脊柱起立筋 - 背骨を支える筋肉',
            self::QUADRICEPS => '大腿四頭筋 - 太ももの前面筋肉',
            self::HAMSTRINGS => 'ハムストリング - 太ももの後面筋肉',
            self::GLUTES => '臀筋 - お尻の筋肉',
            self::CALVES => 'ふくらはぎ - 下腿の筋肉',
            self::ANTERIOR_DELTOID => '三角筋前部 - 肩の前面筋肉',
            self::LATERAL_DELTOID => '三角筋中部 - 肩の側面筋肉',
            self::POSTERIOR_DELTOID => '三角筋後部 - 肩の後面筋肉',
            self::BICEPS_BRACHII => '上腕二頭筋 - 上腕前面の主要筋肉',
            self::TRICEPS_BRACHII => '上腕三頭筋 - 上腕後面の主要筋肉',
            self::FOREARM_FLEXORS => '前腕屈筋群 - 手首・指の屈曲筋',
            self::FOREARM_EXTENSORS => '前腕伸筋群 - 手首・指の伸展筋',
            self::RECTUS_ABDOMINIS => '腹直筋 - 腹部の中央筋肉',
            self::OBLIQUES => '腹斜筋 - 腹部の側面筋肉',
        };
    }

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

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
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
