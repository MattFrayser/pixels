<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Dashboard');
})->name('home');

require __DIR__.'/settings.php';
