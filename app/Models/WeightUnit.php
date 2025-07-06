<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeightUnit extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'symbol',
        'conversion_rate',
    ];

    protected $casts = [
        'conversion_rate' => 'decimal:4',
    ];

    public function exerciseLogs(): HasMany
    {
        return $this->hasMany(ExerciseLog::class);
    }
}
