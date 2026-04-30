<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * These routes are safe to exclude because CSP reports
     * are sent automatically by the browser (not user form submission).
     */
protected $except = [
    '/csp-report',
    '/api/csp-report',
];
}