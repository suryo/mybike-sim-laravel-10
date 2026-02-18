<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\SimulationController;

Route::get('/', [SimulationController::class, 'index']);
Route::post('/bicycles', [SimulationController::class, 'store'])->name('bicycles.store');
Route::patch('/bicycles/{bicycle}', [SimulationController::class, 'update'])->name('bicycles.update');
Route::delete('/bicycles/{bicycle}', [SimulationController::class, 'destroy'])->name('bicycles.destroy');
