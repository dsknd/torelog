<?php

namespace App\Enums;

enum MuscleGroupCategoryEnum: string
{
    case CHEST = 'chest';
    case BACK = 'back';
    case LEGS = 'legs';
    case SHOULDERS = 'shoulders';
    case ABS = 'abs';

    public function getName(): string
    {
        return match($this) {
            self::CHEST => 'Chest',
            self::BACK => 'Back',
            self::LEGS => 'Legs',
            self::SHOULDERS => 'Shoulders',
            self::ABS => 'Abs',
        };
    }

    public function getNameJa(): string
    {
        return match($this) {
            self::CHEST => '胸',
            self::BACK => '背中',
            self::LEGS => '脚',
            self::SHOULDERS => '肩',
            self::ABS => '腹筋',
        };
    }

    public function getOrder(): int
    {
        return match($this) {
            self::CHEST => 1,
            self::BACK => 2,
            self::LEGS => 3,
            self::SHOULDERS => 4,
            self::ABS => 5,
        };
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'name_ja' => $this->getNameJa(),
            'order' => $this->getOrder(),
        ];
    }

    public static function getAllCategories(): array
    {
        return array_map(fn($case) => $case->toArray(), self::cases());
    }
}