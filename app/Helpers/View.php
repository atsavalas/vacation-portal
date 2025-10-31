<?php
namespace App\Helpers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private static $twig;

    public static function render($template, $data = []): void
    {
        if (!self::$twig) {
            $loader = new FilesystemLoader(__DIR__ . '/../../views');
            self::$twig = new Environment($loader, [
                'auto_reload' => true,
                'debug' => true
            ]);

            // globals for accessing across twig template files
            self::$twig->addGlobal('app_name', env('APP_NAME'));
            self::$twig->addGlobal('flash', $_SESSION['flash'] ?? []);
        }

        echo self::$twig->render($template, $data);
    }
}
