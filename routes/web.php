<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CspController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/csp-dashboard', [CspController::class, 'dashboard']);

Route::delete('/csp-clear', [CspController::class, 'clear']);