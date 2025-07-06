<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MuscleGroupCategory extends Model
{
    use HasFactory;
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
