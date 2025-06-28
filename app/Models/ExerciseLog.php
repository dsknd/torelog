<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseLog extends Model
{
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
        'weight' => 'decimal:2',
        'set_number' => 'integer',
        'reps' => 'integer',
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
