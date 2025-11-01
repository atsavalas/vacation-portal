<?php

namespace App\Controllers;

use App\Middleware\Auth;
use App\Models\User;
use App\Validators\UserValidator;

class UserController extends BaseController
{
    private $users;

    private $validator;

    public function __construct()
    {
        $this->users = new User();
        $this->validator = new UserValidator();
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
        $data = [
            'name'          => clean($_POST['name'] ?? ''),
            'email'         => clean($_POST['email'] ?? ''),
            'username'      => clean($_POST['username'] ?? ''),
            'employee_code' => clean($_POST['employee_code'] ?? ''),
            'password'      => $_POST['password'] ?? '',
            'role'          => clean($_POST['role'] ?? 'employee'),
        ];

        $this->validator->validate($data);

        if (!$this->validator->isValid()) {
            setFlash('error', implode('<br>', $this->validator->errors()));
            redirect('/users/create');
        }

        $userExists = $this->users->where([
            'OR' => [
                'username' => $data['username'],
                'email'    => $data['email'],
                'employee_code' => $data['employee_code'],
            ]
        ], true);

        if ($userExists) {
            setFlash('error', 'A user with this username, email, or employee code already exists.');
            redirect('/users/create');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->users->create($data);

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