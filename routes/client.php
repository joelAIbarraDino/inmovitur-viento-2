<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('my-account', function () {
    return Inertia::render('DashboardClient');
})->middleware(['auth', 'verified'])->name('client');