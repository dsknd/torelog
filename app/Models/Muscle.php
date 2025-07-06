<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Muscle extends Model
{
    use HasFactory;
    protected $fillable = [
        'muscle_group_category_id',
        'name',
        'description',
    ];

    public function muscleGroupCategory(): BelongsTo
    {
        return $this->belongsTo(MuscleGroupCategory::class);
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_target_muscles')
            ->withPivot('is_primary')
            ->withTimestamps();
    }
}
