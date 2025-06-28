<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function muscles(): BelongsToMany
    {
        return $this->belongsToMany(Muscle::class, 'exercise_target_muscles')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function muscleGroupCategories(): BelongsToMany
    {
        return $this->belongsToMany(MuscleGroupCategory::class, 'exercise_muscle_group_categories');
    }

    public function trainingMenuExercises(): HasMany
    {
        return $this->hasMany(TrainingMenuExercise::class);
    }

    public function exerciseLogs(): HasMany
    {
        return $this->hasMany(ExerciseLog::class);
    }
}
