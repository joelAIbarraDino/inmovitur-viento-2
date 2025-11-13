<?php

use App\Http\Controllers\CondominiumController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//routes for condominiums 
Route::resource('condominiums', CondominiumController::class)->middleware('auth', 'verified');

require __DIR__.'/settings.php';
