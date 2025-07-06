<?php

namespace App\Enums;

use App\Enums\Concerns\HasBasicMethods;

enum WeightUnitEnum: string
{
    use HasBasicMethods;
    case KILOGRAM = 'kg';
    case POUND = 'lb';
    case GRAM = 'g';

    public function getSymbol(): string
    {
        return $this->value;
    }

    public function getConversionRate(): float
    {
        return match ($this) {
            self::KILOGRAM => 1.0,
            self::POUND => 0.453592,
            self::GRAM => 0.001,
        };
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'value' => $this->value,
            'symbol' => $this->getSymbol(),
            'conversion_rate' => $this->getConversionRate(),
        ];
    }
}
