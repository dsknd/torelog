<?php

namespace Database\Factories;

use App\Models\ExerciseMuscleGroupCategory;
use App\Models\Exercise;
use App\Models\MuscleGroupCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExerciseMuscleGroupCategory>
 */
class ExerciseMuscleGroupCategoryFactory extends Factory
{
    protected $model = ExerciseMuscleGroupCategory::class;

    public function definition(): array
    {
        return [
            'exercise_id' => Exercise::factory(),
            'muscle_group_category_id' => MuscleGroupCategory::factory(),
        ];
    }
}