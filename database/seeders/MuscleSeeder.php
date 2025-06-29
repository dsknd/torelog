<?php

namespace Database\Seeders;

use App\Enums\MuscleEnum;
use App\Models\Muscle;
use App\Models\MuscleGroupCategory;
use Illuminate\Database\Seeder;

class MuscleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (MuscleEnum::cases() as $muscle) {
            $muscleGroupCategory = MuscleGroupCategory::where('name', $muscle->getMuscleGroupCategory()->getName())->first();
            
            if (!$muscleGroupCategory) {
                throw new \Exception("MuscleGroupCategory not found: {$muscle->getMuscleGroupCategory()->getName()}");
            }

            Muscle::updateOrCreate(
                ['name' => $muscle->getName()],
                [
                    'name' => $muscle->getName(),
                    'description' => $muscle->getDescription(),
                    'muscle_group_category_id' => $muscleGroupCategory->id,
                ]
            );
        }
    }
}