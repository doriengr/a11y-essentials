<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutomaticTestController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\PartialResourceController;
use App\Http\Controllers\ProgressController;
use App\Http\Middleware\EnsureUserIsAuthenticated;
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

// Automatic test
Route::get('run-test', [AutomaticTestController::class, 'show'])->name('test.show');
Route::post('run-test', [AutomaticTestController::class, 'run'])->name('test.run');
Route::get('partial/resource/{slug}', [PartialResourceController::class, 'show'])->name('test.resource');

// Checklists
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('checklists', [ChecklistController::class, 'index'])->name('checklists.index');
    Route::get('checklist/create', [ChecklistController::class, 'create'])->name('checklists.create');
    Route::post('checklist', [ChecklistController::class, 'store'])->name('checklists.store');
    Route::get('checklists/{checklist_id}', [ChecklistController::class, 'show'])->name('checklists.show');
    Route::post('checklists/{checklist_id}/states', [ChecklistController::class, 'updateStates'])->name('checklists.states');
    Route::post('checklists/{checklist_id}/groups', [ChecklistController::class, 'updateGroups'])->name('checklists.groups');
});

// Learning progress
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('learning-progress', [ProgressController::class, 'show'])->name('progress.show');
});

    Route::post('learning-progress', [ProgressController::class, 'store'])->name('progress.store');
