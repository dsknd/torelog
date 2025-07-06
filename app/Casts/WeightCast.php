<?php

namespace App\Casts;

use App\Models\WeightUnit;
use App\ValueObjects\Weight;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class WeightCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Weight
    {
        if ($value === null) {
            return null;
        }

        $weightUnitId = $attributes['weight_unit_id'] ?? null;

        if ($weightUnitId === null) {
            throw new \InvalidArgumentException('weight_unit_id is required for Weight cast');
        }

        $weightUnit = WeightUnit::find($weightUnitId);

        if ($weightUnit === null) {
            throw new \InvalidArgumentException("WeightUnit with id {$weightUnitId} not found");
        }

        return new Weight((float) $value, $weightUnit);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [
                $key => null,
                'weight_unit_id' => null,
            ];
        }

        if ($value instanceof Weight) {
            return [
                $key => $value->getValue(),
                'weight_unit_id' => $value->getUnitId(),
            ];
        }

        if (is_array($value) && isset($value['value'], $value['unit_id'])) {
            $weightUnit = WeightUnit::find($value['unit_id']);

            if ($weightUnit === null) {
                throw new \InvalidArgumentException("WeightUnit with id {$value['unit_id']} not found");
            }

            $weight = new Weight($value['value'], $weightUnit);

            return [
                $key => $weight->getValue(),
                'weight_unit_id' => $weight->getUnitId(),
            ];
        }

        if (is_numeric($value)) {
            // For factories/seeding, just store the raw value
            // weight_unit_id should be set separately
            return [
                $key => (float) $value,
            ];
        }

        throw new \InvalidArgumentException('Weight cast expects Weight instance, array with value and unit_id, or numeric value');
    }
}
