<?php
namespace App\Database;

use Medoo\Medoo;
use PDO;

class DB
{
    private static ?Medoo $instance = null;

    public static function connect(): Medoo
    {
        if (!self::$instance) {
            self::$instance = new Medoo([
                'type'     => $_ENV['DB_CONNECTION'],
                'host'     => $_ENV['DB_HOST'],
                'database' => $_ENV['DB_NAME'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'charset'  => 'utf8mb4',
                'error'    => PDO::ERRMODE_EXCEPTION,
            ]);
        }

        return self::$instance;
    }
}
