<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'image',
        'fireBaseSaveREGID',
        'users/*/delete',
        'student/*/delete',
        'event/*/delete',
        'create_school_process',
        'create_batch_process',
    ];
}
