<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PostImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ExpertiseController;
use App\Http\Controllers\Admin\SiteSettingController;

// Public homepage.
Route::get('/', [ProjectController::class, 'index'])
    ->name('home');

// Public project detail page.
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])
    ->name('projects.show');

// Public About page.
Route::get('/about', [AboutController::class, 'show'])
    ->name('about');

// Public contact page.
Route::get('/contact', [ContactController::class, 'show'])
    ->name('contact');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

// Breeze dashboard.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes.
Route::middleware('auth')->group(function () {
    Route::get('/admin/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/admin/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Public blog listing page.
Route::get('/blog', [BlogController::class, 'index'])
    ->name('blog.index');

// Public blog detail page.
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])
    ->name('blog.show');

// Like or unlike a blog post.
Route::post('/blog/{post:slug}/like', [BlogController::class, 'toggleLike'])
    ->name('blog.like');

// Record a blog post share.
Route::post('/blog/{post:slug}/share', [BlogController::class, 'recordShare'])
    ->name('blog.share');

// Blog admin routes.
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Upload images inserted inside Editor.js.
        Route::post('/posts/images', [PostImageController::class, 'store'])
            ->name('posts.images.store');

        // Blog post CRUD.
        Route::resource('posts', PostController::class);

        // Categories are managed directly from one admin page.
        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::patch('/categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');

        //visibility and moving categories up or down
        Route::patch('/categories/{category}/visibility', [CategoryController::class, 'toggleVisibility'])
        ->name('categories.visibility');

        Route::patch('/categories/{category}/move', [CategoryController::class, 'move'])
        ->name('categories.move');

        // Tools and skills are managed together on the Expertise page.
        Route::get('/expertise', [ExpertiseController::class, 'index'])
            ->name('expertise.index');

        Route::post('/expertise/tools', [ExpertiseController::class, 'storeTool'])
            ->name('expertise.tools.store');

        Route::patch('/expertise/tools/{tool}', [ExpertiseController::class, 'updateTool'])
            ->name('expertise.tools.update');

        Route::delete('/expertise/tools/{tool}', [ExpertiseController::class, 'destroyTool'])
            ->name('expertise.tools.destroy');

        Route::patch('/expertise/tools/{tool}/visibility', [ExpertiseController::class, 'toggleToolVisibility'])
            ->name('expertise.tools.visibility');

        Route::patch('/expertise/tools/{tool}/move', [ExpertiseController::class, 'moveTool'])
            ->name('expertise.tools.move');

        Route::post('/expertise/skills', [ExpertiseController::class, 'storeSkill'])
            ->name('expertise.skills.store');

        Route::patch('/expertise/skills/{skill}', [ExpertiseController::class, 'updateSkill'])
            ->name('expertise.skills.update');

        Route::delete('/expertise/skills/{skill}', [ExpertiseController::class, 'destroySkill'])
            ->name('expertise.skills.destroy');

        Route::patch('/expertise/skills/{skill}/visibility', [ExpertiseController::class, 'toggleSkillVisibility'])
            ->name('expertise.skills.visibility');

        Route::patch('/expertise/skills/{skill}/move', [ExpertiseController::class, 'moveSkill'])
            ->name('expertise.skills.move');

        // Contact messages from the public contact form.
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])
            ->name('contact-messages.index');

        Route::get('/contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])
            ->name('contact-messages.show');

        Route::patch('/contact-messages/{contactMessage}/read', [ContactMessageController::class, 'markRead'])
            ->name('contact-messages.read');

        Route::patch('/contact-messages/{contactMessage}/unread', [ContactMessageController::class, 'markUnread'])
            ->name('contact-messages.unread');

        Route::patch('/contact-messages/{contactMessage}/archive', [ContactMessageController::class, 'archive'])
            ->name('contact-messages.archive');

        Route::patch('/contact-messages/{contactMessage}/unarchive', [ContactMessageController::class, 'unarchive'])
            ->name('contact-messages.unarchive');

        Route::delete('/contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])
            ->name('contact-messages.destroy');

        // Site-wide public content and SEO settings.
        Route::get('/site-settings', [SiteSettingController::class, 'edit'])
            ->name('site-settings.edit');

        Route::patch('/site-settings', [SiteSettingController::class, 'update'])
            ->name('site-settings.update');

        
    });

require __DIR__.'/auth.php';
