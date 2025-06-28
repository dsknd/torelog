<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exercise_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->foreignId('weight_unit_id')->constrained()->restrictOnDelete();
            $table->integer('set_number');
            $table->decimal('weight', 8, 2);
            $table->integer('reps');
            $table->text('memo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_logs');
    }
};
