<?php

namespace Database\Seeders;

use App\Enums\ExerciseEnum;
use App\Models\Exercise;
use App\Models\Muscle;
use App\Models\MuscleGroupCategory;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ExerciseEnum::cases() as $exerciseEnum) {
            $exercise = Exercise::updateOrCreate(
                ['name' => $exerciseEnum->getName()],
                [
                    'name' => $exerciseEnum->getName(),
                    'description' => null,
                ]
            );

            // 主要筋肉との関連付け
            $primaryMuscles = $exerciseEnum->getPrimaryMuscles();
            foreach ($primaryMuscles as $muscleEnum) {
                $muscle = Muscle::where('name', $muscleEnum->getName())->first();

                if ($muscle) {
                    $exercise->muscles()->syncWithoutDetaching([
                        $muscle->id => ['is_primary' => true],
                    ]);
                }
            }

            // 筋肉グループカテゴリとの関連付け
            $categories = $exerciseEnum->getMuscleGroupCategories();
            foreach ($categories as $categoryEnum) {
                $category = MuscleGroupCategory::where('name', $categoryEnum->getName())->first();

                if ($category) {
                    $exercise->muscleGroupCategories()->syncWithoutDetaching($category->id);
                }
            }
        }
    }
}
