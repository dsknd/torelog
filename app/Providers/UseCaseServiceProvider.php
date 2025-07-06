<?php

namespace App\Providers;

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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}