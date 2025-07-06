<?php

namespace Database\Seeders;

use App\Enums\WeightUnitEnum;
use App\Models\WeightUnit;
use Illuminate\Database\Seeder;

class WeightUnitSeeder extends Seeder
{
    public function run(): void
    {
        foreach (WeightUnitEnum::cases() as $weightUnit) {
            WeightUnit::updateOrCreate(
                ['symbol' => $weightUnit->getSymbol()],
                [
                    'name' => $weightUnit->getName(),
                    'symbol' => $weightUnit->getSymbol(),
                    'conversion_rate' => $weightUnit->getConversionRate(),
                ]
            );
        }
    }
}
