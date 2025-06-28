<?php

namespace App\ValueObjects;

use App\Models\WeightUnit;
use InvalidArgumentException;

readonly class Weight
{
    public function __construct(
        private float $value,
        private WeightUnit $unit
    ) {
        if ($this->value < 0) {
            throw new InvalidArgumentException('Weight value must be 0 or greater');
        }
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getUnit(): WeightUnit
    {
        return $this->unit;
    }

    public function getUnitId(): int
    {
        return $this->unit->id;
    }

    public function getUnitSymbol(): string
    {
        return $this->unit->symbol;
    }

    /**
     * Convert weight to specified unit
     */
    public function convertTo(WeightUnit $targetUnit): self
    {
        if ($this->unit->id === $targetUnit->id) {
            return $this;
        }

        // Convert to base unit (kg), then to target unit
        $baseValue = $this->value * $this->unit->conversion_rate;
        $targetValue = $baseValue / $targetUnit->conversion_rate;

        return new self($targetValue, $targetUnit);
    }

    /**
     * Convert to kilograms
     */
    public function toKg(): self
    {
        static $kgUnit = null;
        
        if ($kgUnit === null) {
            $kgUnit = WeightUnit::where('symbol', 'kg')->first();
            if (!$kgUnit) {
                throw new InvalidArgumentException('Kg weight unit not found');
            }
        }

        return $this->convertTo($kgUnit);
    }

    public function equals(Weight $other): bool
    {
        $thisInKg = $this->toKg();
        $otherInKg = $other->toKg();
        
        return abs($thisInKg->value - $otherInKg->value) < 0.001; // 0.001kg precision
    }

    public function toString(): string
    {
        return "{$this->value} {$this->unit->symbol}";
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}