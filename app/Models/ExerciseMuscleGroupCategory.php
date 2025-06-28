<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseMuscleGroupCategory extends Model
{
    protected $fillable = [
        'exercise_id',
        'muscle_group_category_id',
    ];

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function muscleGroupCategory(): BelongsTo
    {
        return $this->belongsTo(MuscleGroupCategory::class);
    }
}
