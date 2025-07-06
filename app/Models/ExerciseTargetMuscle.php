<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseTargetMuscle extends Model
{
    use HasFactory;
    protected $fillable = [
        'exercise_id',
        'muscle_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function muscle(): BelongsTo
    {
        return $this->belongsTo(Muscle::class);
    }
}
