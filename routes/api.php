<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CspController;


Route::match(['POST'], '/csp-report', [CspController::class, 'report']);