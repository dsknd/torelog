<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingMenuExercise extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'training_menu_id',
        'exercise_id',
        'order',
    ];

    public function trainingMenu(): BelongsTo
    {
        return $this->belongsTo(TrainingMenu::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
