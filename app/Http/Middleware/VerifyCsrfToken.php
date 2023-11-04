<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'user/username/check',
        'user/check/email',
        '/sslcommerz/success',
        '/sslcommerz/cancel',
        '/sslcommerz/fail',
        'api-parlour-booking/sslcommerz/success',
        'api-parlour-booking/sslcommerz/cancel',
        'api-parlour-booking/sslcommerz/fail'
    ];
}
