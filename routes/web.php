<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
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

// Projects
// TODO: add auth middleware
Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
