<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Task\TaskRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Task\ITaskRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\SubTask\SubTaskRepository;
use App\Repositories\Project\IProjectRepository;
use App\Repositories\SubTask\ISubTaskRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IProjectRepository::class, ProjectRepository::class);
        $this->app->bind(ITaskRepository::class, TaskRepository::class);
        $this->app->bind(ISubTaskRepository::class, SubTaskRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
