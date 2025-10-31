<?php

namespace App\Controllers;

use App\Middleware\Auth;
use App\Models\User;

class UserController extends BaseController
{
    private $users;

    public function __construct()
    {
        $this->users = new User();
        Auth::handle();
    }

    public function index(): void
    {
        $users = $this->users->all();
        view('users/index', ['users' => $users]);
    }

}