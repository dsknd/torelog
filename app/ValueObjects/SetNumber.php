<?php

namespace App\ValueObjects;

use InvalidArgumentException;

readonly class SetNumber
{
    public function __construct(private int $value)
    {
        if ($this->value < 1) {
            throw new InvalidArgumentException('Set number must be 1 or greater');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(SetNumber $other): bool
    {
        return $this->value === $other->value;
    }

    public function next(): self
    {
        return new self($this->value + 1);
    }

    public function previous(): self
    {
        if ($this->value <= 1) {
            throw new InvalidArgumentException('Cannot create set number less than 1');
        }

        return new self($this->value - 1);
    }

    public function isGreaterThan(SetNumber $other): bool
    {
        return $this->value > $other->value;
    }

    public function isLessThan(SetNumber $other): bool
    {
        return $this->value < $other->value;
    }

    public function toString(): string
    {
        return (string) $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
