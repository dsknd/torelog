<?php

namespace App\ValueObjects;

use Carbon\Carbon;
use InvalidArgumentException;

readonly class TrainingDate
{
    private Carbon $date;

    public function __construct(Carbon|string $date)
    {
        if (is_string($date)) {
            try {
                $parsedDate = Carbon::parse($date);
            } catch (\Exception $e) {
                throw new InvalidArgumentException("Invalid date format: {$date}");
            }
        } else {
            $parsedDate = $date->copy();
        }

        // Ensure the date is not in the future
        if ($parsedDate->isFuture()) {
            throw new InvalidArgumentException('Training date cannot be in the future');
        }

        // Set time to beginning of day for consistency
        $this->date = $parsedDate->startOfDay();
    }

    public static function today(): self
    {
        return new self(Carbon::today());
    }

    public static function yesterday(): self
    {
        return new self(Carbon::yesterday());
    }

    public static function fromString(string $date): self
    {
        return new self($date);
    }

    public function getDate(): Carbon
    {
        return $this->date->copy();
    }

    public function format(string $format = 'Y-m-d'): string
    {
        return $this->date->format($format);
    }

    public function equals(TrainingDate $other): bool
    {
        return $this->date->isSameDay($other->date);
    }

    public function isBefore(TrainingDate $other): bool
    {
        return $this->date->isBefore($other->date);
    }

    public function isAfter(TrainingDate $other): bool
    {
        return $this->date->isAfter($other->date);
    }

    public function daysBetween(TrainingDate $other): int
    {
        return abs($this->date->diffInDays($other->date));
    }

    public function isWithinDays(TrainingDate $other, int $days): bool
    {
        return $this->daysBetween($other) <= $days;
    }

    public function isToday(): bool
    {
        return $this->date->isToday();
    }

    public function isYesterday(): bool
    {
        return $this->date->isYesterday();
    }

    public function getWeekday(): string
    {
        return $this->date->format('l'); // Monday, Tuesday, etc.
    }

    public function toString(): string
    {
        return $this->format();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
