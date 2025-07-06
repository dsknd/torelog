<?php

namespace App\Providers;

use App\UseCases\Exercise\SearchExercise\SearchExerciseUseCase;
use App\UseCases\Exercise\SearchExercise\SearchExerciseUseCaseInterface;
use App\UseCases\TrainingRecord\CreateTrainingRecord\CreateTrainingRecordUseCase;
use App\UseCases\TrainingRecord\CreateTrainingRecord\CreateTrainingRecordUseCaseInterface;
use Illuminate\Support\ServiceProvider;

class UseCaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CreateTrainingRecordUseCaseInterface::class,
            CreateTrainingRecordUseCase::class
        );
        
        $this->app->bind(
            SearchExerciseUseCaseInterface::class,
            SearchExerciseUseCase::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}