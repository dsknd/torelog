<?php

namespace App\Casts;

use App\ValueObjects\SetNumber;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SetNumberCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?SetNumber
    {
        if ($value === null) {
            return null;
        }

        return new SetNumber((int) $value);
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

        if ($value instanceof SetNumber) {
            return $value->getValue();
        }

        if (is_int($value)) {
            return (new SetNumber($value))->getValue();
        }

        throw new \InvalidArgumentException('SetNumber cast expects SetNumber instance or integer');
    }
}