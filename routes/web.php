<?php

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\OpenPayController;
use App\Http\Controllers\OpenPayWebhookController;
use App\Http\Controllers\OrderPaymentsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::post('/currency', [CurrencyController::class, 'set'])->name('currency.set');
Route::post('/webhooks/openpay', [OpenPayWebhookController::class, 'handle']);


require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/client.php';
require __DIR__.'/settings.php';
