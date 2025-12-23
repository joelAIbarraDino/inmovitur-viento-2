<?php

use App\Http\Controllers\DashboardClientController;
use Illuminate\Support\Facades\Route;

Route::get('my-account', [DashboardClientController::class, 'index'])->middleware(['auth', 'verified'])->name('client.index');
Route::get('my-documents', [DashboardClientController::class, 'documents'])->middleware(['auth', 'verified'])->name('client.documents');
Route::get('voucher', [DashboardClientController::class, 'voucher'])->middleware(['auth', 'verified'])->name('client.voucher');