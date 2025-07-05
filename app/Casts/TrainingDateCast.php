<?php

namespace App\Casts;

use App\ValueObjects\TrainingDate;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TrainingDateCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?TrainingDate
    {
        if ($value === null) {
            return null;
        }

        return new TrainingDate(Carbon::parse($value));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof TrainingDate) {
            return $value->format('Y-m-d');
        }

        if ($value instanceof Carbon) {
            return (new TrainingDate($value))->format('Y-m-d');
        }

        if (is_string($value)) {
            return (new TrainingDate($value))->format('Y-m-d');
        }

        throw new \InvalidArgumentException('TrainingDate cast expects TrainingDate instance, Carbon instance, or date string');
    }
}
