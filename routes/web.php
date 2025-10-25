<?php

use App\Http\Controllers\AuthController;
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
Route::get('login', [AuthController::class, 'login'])->name('auth.login');
