<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use Illuminate\Support\Facades\Route;

// Everything inside this group requires the user to be logged in.
Route::middleware('auth')->group(function () {

    // This opens the main admin dashboard.
    Route::get('/', DashboardController::class)
        ->name('dashboard');

    // This opens the admin projects page.
    //
    // The full URL will be:
    // /admin/projects
    Route::get('/projects', [ProjectController::class, 'index'])
        ->name('projects.index');

    // This opens the form for adding a new project.
    Route::get('/projects/create', [ProjectController::class, 'create'])
        ->name('projects.create');

    // This receives the form and saves the project.
    Route::post('/projects', [ProjectController::class, 'store'])
        ->name('projects.store');

    // This opens the edit form for one project.
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])
        ->name('projects.edit');

    // This saves the edited project.
    Route::put('/projects/{project}', [ProjectController::class, 'update'])
        ->name('projects.update');

    // This deletes one project.
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy');
});
