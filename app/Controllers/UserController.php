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

        setFlash('success', 'User created.');
        redirect('/users');
    }

    public function edit($id): void
    {
        $user = $this->users->find($id);

        if (!$user) {
            setFlash('error', 'User not found.');
            redirect('/users');
        }

        view('users/edit', [
            'user' => $user,
            'title' => 'Edit User',
        ]);
    }

    public function update($id): void
    {
        $data = [
            'name'          => clean($_POST['name'] ?? ''),
            'email'         => clean($_POST['email'] ?? ''),
            'username'      => clean($_POST['username'] ?? ''),
            'employee_code' => clean($_POST['employee_code'] ?? ''),
            'password'      => $_POST['password'] ?? '',
            'role'          => clean($_POST['role'] ?? 'employee'),
        ];

        $this->validator->validate($data, 'edit');

        if (!$this->validator->isValid()) {
            setFlash('error', implode('<br>', $this->validator->errors()));
            redirect("/users/{$id}/edit");
        }

        // Ensure unique constraints and exclude current user
        $userExists = $this->users->where([
            'AND' => [
                'id[!]' => $id,
                'OR' => [
                    'username'      => $data['username'],
                    'email'         => $data['email'],
                    'employee_code' => $data['employee_code'],
                ]
            ]
        ], true);

        if ($userExists) {
            setFlash('error', 'Another user with this username, email, or employee code already exists.');
            redirect("/users/{$id}/edit");
        }

        if ($data['password']) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->users->update($id, $data);

        setFlash('success', 'User updated.');
        redirect('/users');
    }


    public function delete($id): void
    {
        $this->users->delete($id);
        setFlash('success', 'User deleted');
        redirect('/users');
    }

}