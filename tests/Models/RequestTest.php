<?php

use PHPUnit\Framework\TestCase;
use App\Models\Request;
use App\Validators\RequestValidator;

final class RequestTest extends TestCase
{
    private Request $requestModel;
    private RequestValidator $validator;

    protected function setUp(): void
    {
        $this->requestModel = new Request();
        $this->validator = new RequestValidator();
    }

    public function testCreateValidRequest(): void
    {
        $data = [
            'user_id'    => 1,
            'start_date' => date('Y-m-d', strtotime('+5 days')),
            'end_date'   => date('Y-m-d', strtotime('+10 days')),
            'reason'     => 'Annual leave',
        ];

        $this->validator->validate($data);
        $this->assertTrue($this->validator->isValid(), 'Validator should accept it.');

        $inserted = $this->requestModel->create($data);
        $this->assertNotFalse($inserted, 'Request should be inserted to database.');
    }

    public function testCreateRequestWithInvalidDates(): void
    {
        $data = [
            'user_id'    => 1,
            'start_date' => date('Y-m-d', strtotime('+10 days')),
            'end_date'   => date('Y-m-d', strtotime('+5 days')),
            'reason'     => 'Vacation with wrong dates',
        ];

        $this->validator->validate($data);
        $this->assertFalse(
            $this->validator->isValid(),
            'Validator should reject inconsistent dates.'
        );
    }

    public function testDeleteExistingRequest(): void
    {
        $data = [
            'user_id'    => 1,
            'start_date' => date('Y-m-d', strtotime('+3 days')),
            'end_date'   => date('Y-m-d', strtotime('+6 days')),
            'reason'     => 'Short leave',
        ];

        $id = $this->requestModel->create($data);
        $this->assertNotFalse($id, 'Request should be created (just to delete after).');

        $deleted = $this->requestModel->delete($id);
        $this->assertTrue($deleted, 'Request has been deleted.');
    }
}
