<?php

use App\Http\Controllers\ClientUserController;
use App\Http\Controllers\CondominiumController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SupervisorUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('clients', ClientUserController::class)->middleware('auth', 'verified');
Route::resource('supervisors', SupervisorUserController::class)->middleware('auth', 'verified');
Route::resource('condominiums', CondominiumController::class)->middleware('auth', 'verified');
Route::resource('payments', PaymentController::class)->middleware('auth', 'verified');

Route::middleware('auth', 'verified')->group(function(){
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/stream', [DocumentController::class, 'stream'])->name('documents.stream');
    Route::patch('/documents/{document}/status', [DocumentController::class, 'updateStatus'])->name('document.update-status');
});