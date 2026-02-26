<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BicycleController as AdminBicycleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Dashboard
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('bicycles', AdminBicycleController::class);
    
    // Riders & Fitting
    Route::resource('riders', \App\Http\Controllers\RiderController::class);
    Route::get('fitting/wizard', [\App\Http\Controllers\RiderController::class, 'wizard'])->name('fitting.wizard');
    Route::post('fitting/save', [\App\Http\Controllers\RiderController::class, 'saveFitting'])->name('fitting.save');
});

// Simulation Sessions
Route::get('/sessions', [SimulationController::class, 'listSessions'])->name('sessions.index');
Route::post('/sessions', [SimulationController::class, 'saveSession'])->name('sessions.store');
Route::delete('/sessions/{session}', [SimulationController::class, 'deleteSession'])->name('sessions.destroy');
Route::get('/simulation', [SimulationController::class, 'index'])->name('simulation');
Route::get('/compare', [CompareController::class, 'index'])->name('compare');
Route::get('/drivetrain', [\App\Http\Controllers\DrivetrainController::class, 'index'])->name('drivetrain');

// Planned Routes
Route::get('/planned-routes', [SimulationController::class, 'listPlannedRoutes'])->name('routes.index');
Route::post('/planned-routes', [SimulationController::class, 'savePlannedRoute'])->name('routes.store');
Route::delete('/planned-routes/{route}', [SimulationController::class, 'deletePlannedRoute'])->name('routes.destroy');

// Bicycles (AJAX/API for simulation)
Route::post('/bicycles', [SimulationController::class, 'store'])->name('bicycles.store');
Route::patch('/bicycles/{bicycle}', [SimulationController::class, 'update'])->name('bicycles.update');
Route::delete('/bicycles/{bicycle}', [SimulationController::class, 'destroy'])->name('bicycles.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
