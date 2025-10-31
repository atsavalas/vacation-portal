<?php
namespace App\Helpers;

class Auth
{
    public static function login($user): void
    {
        self::start();
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'role'  => $user['role'],
            'email' => $user['email'],
        ];
    }

    public static function logout(): void
    {
        self::start();

        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public static function user(): mixed
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    private static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
