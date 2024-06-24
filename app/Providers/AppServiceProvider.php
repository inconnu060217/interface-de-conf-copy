<?php

namespace App\Providers;

use App\Services\environmentParameters\EnvironmentParameterRepository;
use App\Services\environmentParameters\EnvironmentParameterService;
use App\Services\environmentParameters\IEnvironmentParameterRepository;
use App\Services\environmentParameters\IEnvironmentParameterService;
use App\Services\logErrors\ILogErrorService;
use App\Services\logErrors\LogErrorService;
use App\Services\mainMenus\IMainMenuRepository;
use App\Services\mainMenus\IMainMenuService;
use App\Services\mainMenus\MainMenuRepository;
use App\Services\mainMenus\MainMenuService;
use App\Services\secondaryMenus\ISecondaryMenuRepository;
use App\Services\secondaryMenus\ISecondaryMenuService;
use App\Services\secondaryMenus\SecondaryMenuRepository;
use App\Services\secondaryMenus\SecondaryMenuService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ILogErrorService::class, LogErrorService::class);
        $this->app->bind(IMainMenuService::class, MainMenuService::class);
        $this->app->bind(IMainMenuRepository::class, MainMenuRepository::class);
        $this->app->bind(ISecondaryMenuService::class, SecondaryMenuService::class);
        $this->app->bind(ISecondaryMenuRepository::class, SecondaryMenuRepository::class);
        $this->app->bind(IEnvironmentParameterRepository::class, EnvironmentParameterRepository::class);
        $this->app->bind(IEnvironmentParameterService::class, EnvironmentParameterService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
