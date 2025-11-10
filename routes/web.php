<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AxeController;
use App\Http\Controllers\PartialResourceController;
use App\Http\Controllers\ProjectController;
use App\Http\Middleware\EnsureUserIsAuthenticated;
use Illuminate\Support\Facades\Route;
use Statamic\Facades\Entry;
use Statamic\View\View;

// The Sitemap route to the sitemap.xml
Route::get('sitemap.xml', function () {
    return response(
        View::make()->template('sitemap/sitemap')->render()
    )->header('Content-Type', 'text/xml');
});

// Authentication
Route::get('register', [AuthController::class, 'register'])->name('auth.registration');
Route::post('register', [AuthController::class, 'registerStore'])->name('auth.registration.store');
Route::get('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('login', [AuthController::class, 'loginStore'])->name('auth.login.store');

// Automatic test
Route::get('run-axe', [AxeController::class, 'show'])->name('axe.show');
Route::post('run-axe', [AxeController::class, 'run'])->name('axe.run');
Route::get('/partial/resource/{slug}', [PartialResourceController::class, 'show'])->name('axe.resource');

// Projects
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
});
