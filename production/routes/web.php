<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubmitController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SignupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Main domain routes (landing page, signup)
Route::domain(config('app.main_domain', 'situationroom.eu'))->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/signup', [SignupController::class, 'show'])->name('signup');
    Route::post('/signup', [SignupController::class, 'store'])->name('signup.store');
    Route::get('/signup/checkout', [SignupController::class, 'checkout'])->name('signup.checkout');
    Route::get('/signup/success', [SignupController::class, 'success'])->name('signup.success');
    Route::get('/signup/cancel', [SignupController::class, 'cancel'])->name('signup.cancel');
});

// Workspace routes (all subdomains except www)
Route::middleware(['workspace'])->group(function () {
    // Public routes
    Route::get('/', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/data', [DashboardController::class, 'data'])->name('dashboard.data');
    Route::get('/focus', [DashboardController::class, 'focusedEntry'])->name('dashboard.focus');

    Route::get('/submit', [SubmitController::class, 'show'])->name('submit');
    Route::post('/submit', [SubmitController::class, 'store'])->name('submit.store');

    // Admin routes (require authentication)
    Route::middleware('auth')->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin');

        // Single entry actions
        Route::post('/entries/{entry}/visible', [AdminController::class, 'toggleVisible'])->name('admin.toggle-visible');
        Route::post('/entries/{entry}/focus', [AdminController::class, 'toggleFocus'])->name('admin.toggle-focus');
        Route::delete('/entries/{entry}', [AdminController::class, 'destroy'])->name('admin.delete-entry');
        Route::post('/entries/{entry}/move', [AdminController::class, 'move'])->name('admin.move-entry');

        // Bulk actions
        Route::post('/show-all', [AdminController::class, 'showAll'])->name('admin.show-all');
        Route::post('/hide-all', [AdminController::class, 'hideAll'])->name('admin.hide-all');
        Route::post('/show-category', [AdminController::class, 'showAllInCategory'])->name('admin.show-category');
        Route::post('/hide-category', [AdminController::class, 'hideAllInCategory'])->name('admin.hide-category');
        Route::post('/purge-all', [AdminController::class, 'purgeAll'])->name('admin.purge-all');

        // Export
        Route::get('/export-pdf', [AdminController::class, 'exportPdf'])->name('admin.export-pdf');
    });
});

// Auth routes (Laravel Breeze will add these)
require __DIR__.'/auth.php';
