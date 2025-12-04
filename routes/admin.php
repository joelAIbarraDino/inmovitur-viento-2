<?php

use App\Http\Controllers\ClientUserController;
use App\Http\Controllers\SupervisorUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('clients', ClientUserController::class)->middleware('auth', 'verified');
Route::resource('supervisors', SupervisorUserController::class)->middleware('auth', 'verified');