<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'training_record_id',
        'exercise_id',
        'weight_unit_id',
        'set_number',
        'weight',
        'reps',
        'memo',
    ];

    protected $casts = [
        'weight' => \App\Casts\WeightCast::class,
        'set_number' => \App\Casts\SetNumberCast::class,
        'reps' => \App\Casts\RepsCast::class,
    ];

    public function trainingRecord(): BelongsTo
    {
        return $this->belongsTo(TrainingRecord::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function weightUnit(): BelongsTo
    {
        return $this->belongsTo(WeightUnit::class);
    }
}
