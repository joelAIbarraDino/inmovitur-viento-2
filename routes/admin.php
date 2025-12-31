<?php

use App\Http\Controllers\SupervisorUserController;
use App\Http\Controllers\OrderPaymentsController;
use App\Http\Controllers\CondominiumController;
use App\Http\Controllers\ClientUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentProfController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfPaymentsController;
use App\Http\Controllers\TowerController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/towers', [TowerController::class, 'towerInformation'])->middleware('auth', 'verified')->name('tower.information');

Route::resource('clients', ClientUserController::class)->middleware('auth', 'verified');
Route::resource('supervisors', SupervisorUserController::class)->middleware('auth', 'verified');
Route::resource('condominiums', CondominiumController::class)->middleware('auth', 'verified');
Route::resource('payments', PaymentController::class)->middleware('auth', 'verified');
Route::resource('order-payments', OrderPaymentsController::class)->middleware('auth', 'verified');
Route::resource('prof-payments', ProfPaymentsController::class)->middleware('auth', 'verified');

Route::middleware('auth', 'verified')->group(function(){
    
    //rutas para control de documentos
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/stream', [DocumentController::class, 'stream'])->name('documents.stream');
    Route::patch('/documents/{document}/status', [DocumentController::class, 'updateStatus'])->name('document.update-status');

    Route::get('/clients/new-contract/{client}', [ClientUserController::class, 'editContract'])->name('client.edit-client');
    Route::post('/clients/new-contract/{client}', [ClientUserController::class, 'updateContract'])->name('client.update-client');

    Route::get('/profDocument/{profPayment}', [DocumentProfController::class, 'show'])->name('profDocument.show');
    Route::get('/profDocument/{profPayment}/stream', [DocumentProfController::class, 'stream'])->name('profDocument.stream');
    Route::patch('/profDocument/{profPayment}/status', [DocumentProfController::class, 'updateStatus'])->name('profDocument.update-status');
});