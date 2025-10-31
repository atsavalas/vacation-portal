<?php

namespace App\Controllers;

abstract class BaseController
{
    protected function getRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}