<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Models\User;

class AuthController extends BaseController
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function login(): void
    {
        if ($this->getRequestMethod() === 'POST') {

            $username = clean($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = $this->user->findByUsername($username);

            if ($user !== null && password_verify($password, $user['password'])) {
                Auth::login($user);
                //TODO: check roles and handle redirect - for now just test
                redirect('/welcome');
            } else {
                setFlash('error', 'Invalid credentials.');
            }
        }

        view('login');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/login');
    }

}
