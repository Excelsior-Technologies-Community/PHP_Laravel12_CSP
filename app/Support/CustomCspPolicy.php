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