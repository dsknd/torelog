<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingMenu extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'training_menu_exercises')
            ->withPivot('order')
            ->orderBy('training_menu_exercises.order');
    }

    public function trainingMenuExercises(): HasMany
    {
        return $this->hasMany(TrainingMenuExercise::class);
    }

    public function trainingRecords(): HasMany
    {
        return $this->hasMany(TrainingRecord::class);
    }
}
