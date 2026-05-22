<?php

if (!function_exists('csp_nonce')) {
    function csp_nonce() {
        return request()->attributes->get('csp_nonce', '');
    }
}