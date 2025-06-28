<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // User Repositories
        $this->app->bind(
            \App\Repositories\Interfaces\UserCommandRepositoryInterface::class,
            \App\Repositories\Eloquent\UserCommandRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\UserQueryRepositoryInterface::class,
            \App\Repositories\Eloquent\UserQueryRepository::class
        );

        // Exercise Repositories
        $this->app->bind(
            \App\Repositories\Interfaces\ExerciseCommandRepositoryInterface::class,
            \App\Repositories\Eloquent\ExerciseCommandRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\ExerciseQueryRepositoryInterface::class,
            \App\Repositories\Eloquent\ExerciseQueryRepository::class
        );

        // TrainingMenu Repositories
        $this->app->bind(
            \App\Repositories\Interfaces\TrainingMenuCommandRepositoryInterface::class,
            \App\Repositories\Eloquent\TrainingMenuCommandRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\TrainingMenuQueryRepositoryInterface::class,
            \App\Repositories\Eloquent\TrainingMenuQueryRepository::class
        );

        // TrainingRecord Repositories
        $this->app->bind(
            \App\Repositories\Interfaces\TrainingRecordCommandRepositoryInterface::class,
            \App\Repositories\Eloquent\TrainingRecordCommandRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\TrainingRecordQueryRepositoryInterface::class,
            \App\Repositories\Eloquent\TrainingRecordQueryRepository::class
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
