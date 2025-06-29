<?php

namespace App\Enums;

enum MuscleGroupCategoryEnum: string
{
    case CHEST = 'chest';
    case BACK = 'back';
    case LEGS = 'legs';
    case SHOULDERS = 'shoulders';
    case ARMS = 'arms';
    case ABS = 'abs';

    public function getName(): string
    {
        return match($this) {
            self::CHEST => 'Chest',
            self::BACK => 'Back',
            self::LEGS => 'Legs',
            self::SHOULDERS => 'Shoulders',
            self::ARMS => 'Arms',
            self::ABS => 'Abs',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::CHEST => '胸部の筋肉群',
            self::BACK => '背中の筋肉群',
            self::LEGS => '脚部の筋肉群',
            self::SHOULDERS => '肩部の筋肉群',
            self::ARMS => '腕部の筋肉群',
            self::ABS => '腹部の筋肉群',
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