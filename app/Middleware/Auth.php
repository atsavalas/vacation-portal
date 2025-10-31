<?php

namespace App\Middleware;

use App\Helpers\Auth as Authentication;

class Auth
{
    public static function handle(): void
    {
        if (!Authentication::check()) {
            redirect('/login');
        }
    }
}