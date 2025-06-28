<?php

namespace App\Enums;

enum MuscleEnum: string
{
    // 上半身
    case CHEST = 'chest';
    case SHOULDERS = 'shoulders';
    case TRICEPS = 'triceps';
    case BICEPS = 'biceps';
    case FOREARMS = 'forearms';
    case LATS = 'lats';
    case RHOMBOIDS = 'rhomboids';
    case MIDDLE_TRAPS = 'middle_traps';
    case LOWER_TRAPS = 'lower_traps';
    case REAR_DELTS = 'rear_delts';

    // 下半身
    case QUADRICEPS = 'quadriceps';
    case HAMSTRINGS = 'hamstrings';
    case GLUTES = 'glutes';
    case CALVES = 'calves';
    case ADDUCTORS = 'adductors';
    case ABDUCTORS = 'abductors';

    // 体幹
    case ABS = 'abs';
    case OBLIQUES = 'obliques';
    case LOWER_BACK = 'lower_back';

    public function getName(): string
    {
        return match($this) {
            self::CHEST => '胸筋',
            self::SHOULDERS => '肩',
            self::TRICEPS => '上腕三頭筋',
            self::BICEPS => '上腕二頭筋',
            self::FOREARMS => '前腕',
            self::LATS => '広背筋',
            self::RHOMBOIDS => '菱形筋',
            self::MIDDLE_TRAPS => '僧帽筋（中部）',
            self::LOWER_TRAPS => '僧帽筋（下部）',
            self::REAR_DELTS => '三角筋（後部）',
            self::QUADRICEPS => '大腿四頭筋',
            self::HAMSTRINGS => 'ハムストリング',
            self::GLUTES => '臀筋',
            self::CALVES => 'ふくらはぎ',
            self::ADDUCTORS => '内転筋',
            self::ABDUCTORS => '外転筋',
            self::ABS => '腹筋',
            self::OBLIQUES => '腹斜筋',
            self::LOWER_BACK => '脊柱起立筋',
        };
    }

    public function getMuscleGroupCategory(): MuscleGroupCategoryEnum
    {
        return match($this) {
            self::CHEST, self::SHOULDERS, self::TRICEPS, self::BICEPS, 
            self::FOREARMS, self::LATS, self::RHOMBOIDS, self::MIDDLE_TRAPS, 
            self::LOWER_TRAPS, self::REAR_DELTS => MuscleGroupCategoryEnum::UPPER_BODY,
            
            self::QUADRICEPS, self::HAMSTRINGS, self::GLUTES, self::CALVES,
            self::ADDUCTORS, self::ABDUCTORS => MuscleGroupCategoryEnum::LOWER_BODY,
            
            self::ABS, self::OBLIQUES, self::LOWER_BACK => MuscleGroupCategoryEnum::CORE,
        };
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
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