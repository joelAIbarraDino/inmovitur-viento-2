<?php

use App\Http\Controllers\Auth\AfterLoginController;
use Illuminate\Support\Facades\Route;

Route::get('after-login', [AfterLoginController::class, 'handle'])->middleware(['auth'])->name('after.login');