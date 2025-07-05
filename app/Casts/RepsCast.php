<?php

namespace App\Casts;

use App\ValueObjects\Reps;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class RepsCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Reps
    {
        if ($value === null) {
            return null;
        }

        return new Reps((int) $value);
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

        if ($value instanceof Reps) {
            return $value->getValue();
        }

        if (is_int($value)) {
            return (new Reps($value))->getValue();
        }

        throw new \InvalidArgumentException('Reps cast expects Reps instance or integer');
    }
}
