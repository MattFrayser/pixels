<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Inertia\Inertia;

Route::get('/', function () {
    return inertia('Dashboard');
})->name('home');

require __DIR__.'/settings.php';
