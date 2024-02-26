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
        'change-brightness',
        'get-brightness',
        'send-image',
        'get-currentNumber',
        'send-specNumber',
        'read-information',
        'swith-mode',
        'get-current-playlist',
        'send_img_playlist',
    ];
}
