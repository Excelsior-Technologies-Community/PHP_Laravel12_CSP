<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CspMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);
        
        $cspHeader = "default-src 'self'; " .
                     "script-src 'self' 'unsafe-inline' 'nonce-{$nonce}'; " .
                     "style-src 'self' 'unsafe-inline'; " .
                     "img-src 'self' data:; " .
                     "font-src 'self'; " .
                     "connect-src 'self'; " .
                     "frame-src 'none'; " .
                     "object-src 'none'; " .
                     "report-uri /csp-report";
        
        $response->headers->set('Content-Security-Policy', $cspHeader);
        
        return $response;
    }
}