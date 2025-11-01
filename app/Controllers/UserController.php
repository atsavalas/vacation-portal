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

    public function create(): void
    {
        view('users/create');
    }

    public function store(): void
    {
        $name = clean($_POST['name'] ?? '');
        $employee_code = clean($_POST['employee_code'] ?? '');
        $email = clean($_POST['email'] ?? '');
        $username = clean($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = clean($_POST['role'] ?? '');

        if (!$name || !$employee_code || !$email || !$username || !$password || !$role) {
            setFlash('error', 'All fields are required.');
            redirect('/users/create');
        }

        $userExists = $this->users->where([
            'OR' => [
                'username' => $username,
                'email'    => $email
            ]
        ], true);

        if ($userExists) {
            setFlash('error', 'A user with this username or email already exists.');
            redirect('/users/create');
        }

        $this->users->create([
            'name'          => $name,
            'employee_code' => $employee_code,
            'email'         => $email,
            'username'      => $username,
            'password'      => password_hash($password, PASSWORD_BCRYPT),
            'role'          => $role
        ]);

        setFlash('success', 'User created successfully.');
        redirect('/users');
    }

    public function delete($id): void
    {
        $this->users->delete($id);
        setFlash('success', 'User deleted');
        redirect('/users');
    }

}