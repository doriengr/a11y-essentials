<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutomaticTestController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\PartialRequirementController;
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
Route::get('registrierung', [AuthController::class, 'register'])->name('auth.register');
Route::post('register', [AuthController::class, 'registerStore'])->name('auth.register.store');
Route::get('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('login', [AuthController::class, 'loginStore'])->name('auth.login.store');
Route::get('auth/logged-in-user', [AuthController::class, 'loggedInUser'])->name('auth.user');

// Automatic test
Route::get('automatischer-test', [AutomaticTestController::class, 'show'])->name('test.show');
Route::post('run-test', [AutomaticTestController::class, 'run'])->name('test.run');
Route::get('partial/requirement/{rule}', [PartialRequirementController::class, 'show'])->name('test.requirement');

// Checklists
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('checklisten', [ChecklistController::class, 'index'])->name('checklists.index');
    Route::get('checklisten/checkliste-erstellen', [ChecklistController::class, 'create'])->name('checklists.create');
    Route::post('checklist', [ChecklistController::class, 'store'])->name('checklists.store');
    Route::get('checklisten/{checklist_id}', [ChecklistController::class, 'show'])->name('checklists.show');
    Route::post('checklists/{checklist_id}/states', [ChecklistController::class, 'updateStates'])->name('checklists.states');
    Route::post('checklists/{checklist_id}/groups', [ChecklistController::class, 'updateGroups'])->name('checklists.groups');
});

// Learning progress
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('lernfortschritt', [ProgressController::class, 'show'])->name('progress.show');
    Route::post('learning-progress', [ProgressController::class, 'store'])->name('progress.store');
});
