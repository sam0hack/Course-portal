<?php

namespace App\Providers;

use App\Repositories\Achievements\AchievementsRepository;
use App\Repositories\Achievements\AchievementsRepositoryInterface;
use App\Repositories\Achievements\BadgesRepository;
use App\Repositories\Achievements\BadgesRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AchievementsRepositoryInterface::class, AchievementsRepository::class);
        $this->app->singleton(BadgesRepositoryInterface::class,BadgesRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
