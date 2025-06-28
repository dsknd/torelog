<?php

namespace Database\Seeders;

use App\Enums\MuscleGroupCategoryEnum;
use App\Models\MuscleGroupCategory;
use Illuminate\Database\Seeder;

class MuscleGroupCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (MuscleGroupCategoryEnum::cases() as $category) {
            MuscleGroupCategory::updateOrCreate(
                ['name' => $category->getName()],
                $category->toArray()
            );
        }
    }
}