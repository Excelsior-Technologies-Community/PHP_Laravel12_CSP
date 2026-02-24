# PHP_Laravel12_CSP

## Project Description: 

PHP_Laravel12_CSP is a Laravel 12 based project demonstrating how to implement Content Security Policy (CSP) using the Spatie CSP package. CSP is a security feature that helps prevent cross-site scripting (XSS), clickjacking, and other code injection attacks by controlling which scripts, styles, fonts, and resources a browser can load.


## Features

1. CSP Integration – Implements Content Security Policy headers to secure your Laravel application from XSS, clickjacking, and unsafe content injection.

2. Custom CSP Policy – Allows defining trusted scripts, styles, fonts, and images.

3. Nonce Support – Inline scripts and styles are allowed securely using automatically generated nonce values.

4. CDN and External Resource Support – Whitelists external resources like Google Fonts, CDNJS, and JSDelivr safely.

5. Middleware-Based Security – CSP headers are applied globally via middleware, ensuring consistency across all routes.

6. Clean Blade Templates – Demonstrates best practices for using CSP nonces in Blade views.

7. Easy Cache Management – Clear and refresh caches for immediate effect of CSP changes.

8. Educational Purpose – Step-by-step guide to integrate CSP in a Laravel 12 application.


## Technologies Used

• Laravel 12 – PHP framework for MVC applications
• PHP 8.2 – Server-side scripting language
• Spatie Laravel CSP – Content Security Policy package
• HTML & CSS – Frontend markup and styling
• Composer & Artisan – Dependency and CLI tools


---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_CSP "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_CSP

```

#### Explanation:

Installs a fresh Laravel 12 project and navigates into the project folder.




## STEP 2: Install Spatie Laravel CSP Package

### Run:

```
composer require spatie/laravel-csp

```

#### Explanation:

Adds the Spatie CSP package to your Laravel project for managing Content Security Policies.




## STEP 3: Publish CSP Config File

### Run:

```
php artisan vendor:publish --tag=csp-config

```

### Output:

```
Copied File [config/csp.php]

```

#### Explanation:

Publishes the CSP configuration file to config/csp.php so you can customize CSP rules.




## STEP 4: Configure CSP Middleware

### Open: bootstrap/app.php

#### Replace:

```
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Spatie\Csp\AddCspHeaders;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        $middleware->web(append: [
            AddCspHeaders::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    ->create();

```

#### Explanation:

This adds CSP headers to all responses.

Reference confirms middleware registration method.




## STEP 5: Configure CSP Settings

### Open: config/csp.php

#### Modify:

```
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

```

#### Explanation:

Specifies which CSP rules to use and enables nonce support for inline scripts/styles.




## STEP 6: Create Custom CSP Policy

### Create folder:

```
app/Support

```

### Create file:

```
app/Support/CustomCspPolicy.php

```

### Code: app/Support/CustomCspPolicy.php

```
<?php

namespace App\Support;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class CustomCspPolicy implements Preset
{
    public function configure(\Spatie\Csp\Policy $policy): void
    {
        $policy
            // Default sources
            ->add(Directive::DEFAULT, 'self')

            // Scripts (inline + external)
            ->add(Directive::SCRIPT, [
                'self',
                'https://cdnjs.cloudflare.com',
                'https://cdn.jsdelivr.net',
            ])
            ->addNonce(Directive::SCRIPT) // inline scripts

            // Inline styles
            ->add(Directive::STYLE, [
                'self',
            ])
            ->addNonce(Directive::STYLE) // inline <style>

            // External stylesheets (Google Fonts) → NO NONCE!
            ->add(Directive::STYLE_ELEM, [
                'https://fonts.googleapis.com',
            ])

            // Fonts
            ->add(Directive::FONT, [
                'self',
                'https://fonts.gstatic.com',
            ])

            // Images
            ->add(Directive::IMG, [
                'self',
                'data:',
            ]);
    }
}

```

#### Explanation:

Defines a custom CSP policy controlling allowed scripts, styles, fonts, and images.




## STEP 7: Routes

### Edit routes/web.php:

```
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

```

#### Explanation:

Sets up a simple route to load your main Blade view.





## STEP 8: Blade Template

### Edit resources/views/welcome.blade.php:

```
<!DOCTYPE html>
<html>

<head>
    <title>Laravel 12 CSP</title>


    <!-- Inline style with nonce -->
    <style nonce="{{ csp_nonce() }}">
        body {
            font-family: 'Roboto', sans-serif;
            background: #f8fafc;
            padding: 20px;
        }

        h1 {
            color: #111827;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 40px auto;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Laravel 12 CSP Working</h1>
    </div>

    <script nonce="{{ csp_nonce() }}">
        console.log("CSP Script Allowed");
    </script>
</body>

</html>

```

#### Explanation:

Uses nonces for inline scripts/styles so they pass the CSP checks while allowing Google Fonts externally.





## STEP 9: Clear Cache

### Run:

```
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
composer dump-autoload

```

#### Explanation:

Clears all cached configuration and compiled files so CSP changes take effect immediately.





## STEP 10: Test in Browser

### Run: 

```
php artisan serve

```

### Open:

```
http://127.0.0.1:8000

```

#### Explanation:

Serves your Laravel project locally to verify CSP is working correctly.





## Expected Output:

1. White card in the middle: “Laravel 12 CSP Working”

2. Google Fonts applied (Roboto)

3. Console shows:

```
CSP Script Allowed

```

4. No CSP errors in console

### Example:

<img width="1919" height="970" alt="Screenshot 2026-02-24 143936" src="https://github.com/user-attachments/assets/73183321-e943-4c40-89d9-5a0cd85cc29f" />

---

# Project Folder Structure:

```
PHP_Laravel12_CSP/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Support/
│       └── CustomCspPolicy.php      ← Your custom CSP policy
├── bootstrap/
│   └── app.php                      ← Middleware registration here
├── config/
│   ├── app.php
│   ├── csp.php                      ← CSP configuration file
│   └── ... (other Laravel config files)
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── index.php
│   └── ... (css, js, images if any)
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       └── welcome.blade.php        ← Blade template with CSP nonces
├── routes/
│   └── web.php                      ← Main route file
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
├── tests/
├── vendor/
├── .env
├── artisan
├── composer.json
├── composer.lock
└── phpunit.xml

```


