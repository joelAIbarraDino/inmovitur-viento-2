<?php

use App\Http\Controllers\DashboardClientController;
use Illuminate\Support\Facades\Route;

Route::get('my-account', [DashboardClientController::class, 'index'])->middleware(['auth', 'verified'])->name('client.index');
Route::get('my-files', [DashboardClientController::class, 'documents'])->middleware(['auth', 'verified'])->name('client.documents');

Route::get('voucher', [DashboardClientController::class, 'voucher'])->middleware(['auth', 'verified'])->name('client.voucher');

Route::get('file-upload/{typeFile}', [DashboardClientController::class, 'uploadDocument'])->middleware(['auth', 'verified'])->name('client.document-create');
Route::post('file-upload', [DashboardClientController::class, 'storeDocument'])->middleware(['auth', 'verified'])->name('client.store-document');

Route::get('prof-upload/{profPayment}', [DashboardClientController::class, 'uploadProf'])->middleware(['auth', 'verified'])->name('client.prof-create');
Route::post('prof-upload', [DashboardClientController::class, 'storeProf'])->middleware(['auth', 'verified'])->name('client.store-prof');


Route::middleware('auth', 'verified')->group(function(){
    //rutas para control de documentos
    Route::get('/file-view/{document}', [DashboardClientController::class, 'showDocument'])->name('client.show-document');
    Route::get('/file-view/{document}/stream', [DashboardClientController::class, 'streamDocument'])->name('client.stream-document');
    Route::get('/file-view/{document}/edit', [DashboardClientController::class, 'editDocument'])->name('client.edit-document');
    Route::put('/file-view/{document}', [DashboardClientController::class, 'updateDocument'])->name('client.update-document');
});
