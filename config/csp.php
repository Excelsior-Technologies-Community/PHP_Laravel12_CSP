<?php

use Spatie\Csp\Presets\Basic;
use App\Support\CustomCspPolicy;

return [

    'presets' => [
        \Spatie\Csp\Presets\Basic::class,
        \App\Support\CustomCspPolicy::class,
    ],

    'directives' => [

    ],

    'report_only_presets' => [

    ],


    'report_only_directives' => [

    ],

    'report_uri' => null,

    'enabled' => env('CSP_ENABLED', true),

    'enabled_while_hot_reloading' => false,

    'nonce_generator' => Spatie\Csp\Nonce\RandomString::class,

    'nonce_enabled' => true,

];