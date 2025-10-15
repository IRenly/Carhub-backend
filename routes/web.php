<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return response()->json([
        'message' => 'Servidor Laravel corriendo en la nube👌',
        'status' => 'ok'
    ]);
})->name('home');
