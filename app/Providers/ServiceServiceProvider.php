<?php

namespace App\Providers;

use App\Services\User\UserService;
use App\Services\User\IUserService;
use Illuminate\Support\ServiceProvider;
use App\Services\Project\ProjectService;
use App\Services\Project\IProjectService;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IProjectService::class, ProjectService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
