<?php

use App\Http\Controllers\SupervisorUserController;
use App\Http\Controllers\CondominiumController;
use App\Http\Controllers\ClientUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TowerController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('clients', ClientUserController::class)->middleware('auth', 'verified');
Route::resource('supervisors', SupervisorUserController::class)->middleware('auth', 'verified');
Route::resource('condominiums', CondominiumController::class)->middleware('auth', 'verified');
Route::resource('payments', PaymentController::class)->middleware('auth', 'verified');

Route::middleware('auth', 'verified')->group(function(){
    //rutas para control de documentos
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/stream', [DocumentController::class, 'stream'])->name('documents.stream');
    Route::patch('/documents/{document}/status', [DocumentController::class, 'updateStatus'])->name('document.update-status');

    
    Route::get('/towers', [TowerController::class, 'towerInformation'])->name('tower.information');
});