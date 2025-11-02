<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Middleware\Auth as MiddlewareAuth;
use App\Models\Request;
use App\Validators\RequestValidator;

class RequestController extends BaseController
{
    private $user;

    private $requests;

    private $validator;

    public function __construct()
    {
        MiddlewareAuth::handle();
        $this->requests = new Request();
        $this->validator = new RequestValidator();
        $this->user = Auth::user();
    }

    public function index(): void
    {
        if ($this->user['role'] === 'manager') {
            $requests = $this->requests->all();
        } else {
            $requests = $this->requests->where(['user_id' => $this->user['id']]);
        }

        view('requests/index', ['requests' => $requests]);
    }

    public function create(): void
    {
        view('requests/create');
    }

    public function store(): void
    {
        $data = [
            'start_date' => $_POST['start_date'] ?? '',
            'end_date'   => $_POST['end_date'] ?? '',
            'reason'     => clean($_POST['reason'] ?? ''),
        ];

        $this->validator->validate($data);

        if (!$this->validator->isValid()) {
            setFlash('error', implode('<br>', $this->validator->errors()));
            redirect('/requests/create');
        }

        $data['user_id'] = $this->user['id'];
        $this->requests->create($data);

        setFlash('success', 'Vacation request submitted.');
        redirect('/requests');
    }
}