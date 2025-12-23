<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::post('/currency', [CurrencyController::class, 'set'])->name('currency.set');


require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/client.php';
require __DIR__.'/settings.php';
