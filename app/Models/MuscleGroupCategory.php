<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MuscleGroupCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function muscles(): HasMany
    {
        return $this->hasMany(Muscle::class);
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_muscle_group_categories');
    }
}
