<?php

namespace App\Enums;

enum MuscleGroupCategoryEnum: string
{
    case UPPER_BODY = 'upper_body';
    case LOWER_BODY = 'lower_body';
    case CORE = 'core';
    case FULL_BODY = 'full_body';
    case CARDIO = 'cardio';

    public function getName(): string
    {
        return match($this) {
            self::UPPER_BODY => '上半身',
            self::LOWER_BODY => '下半身',
            self::CORE => '体幹',
            self::FULL_BODY => '全身',
            self::CARDIO => '有酸素',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::UPPER_BODY => '上半身の筋肉群',
            self::LOWER_BODY => '下半身の筋肉群',
            self::CORE => '体幹部の筋肉群',
            self::FULL_BODY => '全身を使う運動',
            self::CARDIO => '心肺機能向上運動',
        };
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
        ];
    }

    public static function getAllCategories(): array
    {
        return array_map(fn($case) => $case->toArray(), self::cases());
    }
}