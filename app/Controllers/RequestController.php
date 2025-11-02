<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Middleware\Auth as MiddlewareAuth;
use App\Middleware\Role as MiddlewareRole;
use App\Models\Request;
use App\Validators\RequestValidator;

class RequestController extends BaseController
{
    private mixed $user;

    private Request $requests;

    private RequestValidator $validator;

    public function __construct()
    {
        MiddlewareAuth::handle();
        MiddlewareRole::handle(['manager', 'employee']);
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

        $totalPendingRequests = $this->requests->count([
            'status' => 'pending',
            'user_id[!]' => $this->user['id']
        ]);
        view('requests/index', ['requests' => $requests, 'totalPendingRequests' => $totalPendingRequests]);
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
            $errors = [];
            foreach ($this->validator->errors() as $fieldErrors) {
                if (is_array($fieldErrors)) {
                    $errors = array_merge($errors, $fieldErrors);
                } else {
                    $errors[] = $fieldErrors;
                }
            }
            setFlash('error', implode('<br>', $errors));
            redirect('/requests/create');
        }

        $data['user_id'] = $this->user['id'];
        $this->requests->create($data);

        setFlash('success', 'Vacation request submitted.');
        redirect('/requests');
    }

    public function approve($id): void
    {
        $this->checkRequestBeforeProcessing($id);
        $this->requests->update($id, ['status' => 'approved']);
        setFlash('success', 'Vacation request approved.');
        redirect('/requests');
    }


    public function reject($id): void
    {
        $this->checkRequestBeforeProcessing($id);
        $this->requests->update($id, ['status' => 'rejected']);
        setFlash('success', 'Vacation request rejected.');
        redirect('/requests');
    }

    public function delete($id): void
    {
        $request = $this->requests->find($id);

        if (!$request) {
            setFlash('error', 'Vacation request not found.');
            redirect('/requests');
        }

        // Allow deletion if pending request belongs to auth user
        $isOwner = $request['user_id'] === $this->user['id'];

        if (!$isOwner) {
            setFlash('error', 'You are not allowed to delete this vacation request.');
            redirect('/requests');
        }

        if ($isOwner && $request['status'] !== 'pending') {
            setFlash('error', 'You can only delete pending vacation requests.');
            redirect('/requests');
        }

        $this->requests->delete($id);

        setFlash('success', 'Vacation request deleted.');
        redirect('/requests');
    }

    private function checkRequestBeforeProcessing($id): void
    {
        // Only managers can approve/reject
        if ($this->user['role'] !== 'manager') {
            setFlash('error', 'You are not authorized to perform this action.');
            redirect('/requests');
        }

        $request = $this->requests->find($id);

        if (!$request) {
            setFlash('error', 'Vacation request not found.');
            redirect('/requests');
        }

        if ($request['status'] !== 'pending') {
            setFlash('error', 'This request has already been processed.');
            redirect('/requests');
        }

        if ($request['user_id'] === $this->user['id']) {
            setFlash('error', 'You are not allowed to perform this action.');
            redirect('/requests');
        }
    }

}