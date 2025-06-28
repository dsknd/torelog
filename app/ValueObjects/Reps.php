<?php

namespace App\ValueObjects;

use InvalidArgumentException;

readonly class Reps
{
    public function __construct(private int $value)
    {
        if ($this->value < 1) {
            throw new InvalidArgumentException('Reps must be 1 or greater');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(Reps $other): bool
    {
        return $this->value === $other->value;
    }

    public function add(Reps $other): self
    {
        return new self($this->value + $other->value);
    }

    public function isGreaterThan(Reps $other): bool
    {
        return $this->value > $other->value;
    }

    public function isLessThan(Reps $other): bool
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