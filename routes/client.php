<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('my-account', function () {
    // return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('client');