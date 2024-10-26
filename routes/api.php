<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SubTaskController;
use App\Http\Controllers\Api\Auth\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    Route::controller(ProjectController::class)->prefix('projects')->group(function () {
        Route::get('', 'index');
        Route::post('', 'store');
        Route::get('/{project_id}', 'show');
        Route::put('/{project_id}', 'update');
        Route::delete('/{project_id}', 'destroy');
    });

    Route::controller(TaskController::class)->prefix('tasks')->group(function () {
        Route::get('', 'index');
        Route::get('/{task_id}', 'show');
        Route::post('', 'store');
        Route::put('/{task_id}', 'update');
        Route::delete('/{task_id}', 'destroy');
    });

    Route::get('tasks/{id}/sub-tasks', [SubTaskController::class, 'index']);

    Route::controller(SubTaskController::class)->prefix('sub-tasks')->group(function () {
        Route::get('/{sub_task_id}', 'show');
        Route::post('', 'store');
        Route::put('/{sub_task_id}', 'update');
        Route::delete('/{sub_task_id}', 'destroy');
    });

    Route::controller(TagController::class)->prefix('tags')->group(function () {
        Route::get('', 'index');
        Route::post('', 'store');
        Route::get('/{tag_id}', 'show');
        Route::put('/{tag_id}', 'update');
        Route::delete('/{tag_id}', 'destroy');
    });
});

