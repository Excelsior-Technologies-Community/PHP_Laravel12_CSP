<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CspController;
use App\Models\CspLog;
use App\Models\BlockedDomain;

// Home page with statistics
Route::get('/', function () {
    $stats = [
        'total' => CspLog::count(),
        'today' => CspLog::whereDate('created_at', today())->count(),
        'blocked_domains' => BlockedDomain::count(),
    ];
    
    return view('welcome', compact('stats'));
});

// CSP Routes
Route::post('/csp-report', [CspController::class, 'report']);
Route::get('/csp-dashboard', [CspController::class, 'dashboard']);
Route::delete('/csp-clear', [CspController::class, 'clear']);
Route::delete('/csp-log/{id}', [CspController::class, 'deleteLog']);
Route::post('/csp-bulk-delete', [CspController::class, 'bulkDelete']);
Route::post('/csp-mark-read', [CspController::class, 'markRead']);
Route::post('/csp-add-domain', [CspController::class, 'addDomain']);
Route::delete('/csp-remove-domain/{id}', [CspController::class, 'removeDomain']);
Route::get('/csp-export', [CspController::class, 'export']);