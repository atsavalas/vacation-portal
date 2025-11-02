<?php

namespace App\Middleware;

use App\Helpers\Auth as Authentication;

class Role
{
    public static function handle(array $allowedRoles): bool
    {
        $user = Authentication::user();
        if (!$user || !in_array($user['role'], $allowedRoles)) {
            http_response_code(401);
            return false;
        }

        return true;
    }
}