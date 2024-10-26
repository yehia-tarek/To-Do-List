<?php

namespace App\Providers;

use App\Services\Tag\TagService;
use App\Services\Tag\ITagService;
use App\Services\Task\TaskService;
use App\Services\User\UserService;
use App\Services\Task\ITaskService;
use App\Services\User\IUserService;
use Illuminate\Support\ServiceProvider;
use App\Services\Comment\CommentService;
use App\Services\Project\ProjectService;
use App\Services\SubTask\SubTaskService;
use App\Services\Comment\ICommentService;
use App\Services\Project\IProjectService;
use App\Services\SubTask\ISubTaskService;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IProjectService::class, ProjectService::class);
        $this->app->bind(ITaskService::class, TaskService::class);
        $this->app->bind(ISubTaskService::class, SubTaskService::class);
        $this->app->bind(ITagService::class, TagService::class);
        $this->app->bind(ICommentService::class, CommentService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
