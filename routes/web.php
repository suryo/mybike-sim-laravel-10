<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimulationController;

Route::get('/', [SimulationController::class, 'index']);
Route::post('/bicycles', [SimulationController::class, 'store'])->name('bicycles.store');
Route::patch('/bicycles/{bicycle}', [SimulationController::class, 'update'])->name('bicycles.update');
Route::delete('/bicycles/{bicycle}', [SimulationController::class, 'destroy'])->name('bicycles.destroy');

// Simulation Sessions
Route::post('/sessions', [SimulationController::class, 'saveSession'])->name('sessions.store');
Route::get('/sessions', [SimulationController::class, 'listSessions'])->name('sessions.index');
Route::delete('/sessions/{session}', [SimulationController::class, 'deleteSession'])->name('sessions.destroy');
