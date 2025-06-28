<?php

namespace App\Enums;

enum WeightUnitEnum: string
{
    case KILOGRAM = 'kg';
    case POUND = 'lb';
    case GRAM = 'g';

    public function getName(): string
    {
        return match($this) {
            self::KILOGRAM => 'キログラム',
            self::POUND => 'ポンド', 
            self::GRAM => 'グラム',
        };
    }

    public function getSymbol(): string
    {
        return $this->value;
    }

    public function getConversionRate(): float
    {
        return match($this) {
            self::KILOGRAM => 1.0,
            self::POUND => 0.453592,
            self::GRAM => 0.001,
        };
    }

    public static function getDefault(): self
    {
        return self::KILOGRAM;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'symbol' => $this->getSymbol(),
            'conversion_rate' => $this->getConversionRate(),
        ];
    }
}