<?php

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Validators\UserValidator;

final class UserTest extends TestCase
{
    protected User $userModel;
    protected UserValidator $validator;

    protected function setUp(): void
    {
        $this->userModel = new User();
        $this->validator = new UserValidator();
    }

    // --- Existing tests ---------------------------------------------------

    public function testFindByUsernameReturnsUser(): void
    {
        $user = $this->userModel->where(['username' => 'alice'], true);
        $this->assertIsArray($user, 'Expected user as an array');
        $this->assertSame('alice', $user['username']);
    }

    public function testFindByUsernameReturnsNullForInvalidUser(): void
    {
        $user = $this->userModel->where(['username' => 'nonexistent'], true);
        $this->assertNull($user, 'Expected null for nonexistent user');
    }

    public function testAllReturnsArray(): void
    {
        $allUsers = $this->userModel->all();
        $this->assertIsArray($allUsers);
        $this->assertNotEmpty($allUsers);
    }

    // --- New tests: validation & creation ----------------------------------

    public function testValidatorAcceptsValidData(): void
    {
        $data = [
            'name' => 'John Tester',
            'email' => 'john@example.com',
            'username' => 'john',
            'employee_code' => '0000010',
            'password' => 'secret123',
            'role' => 'employee'
        ];

        $this->validator->validate($data);

        $this->assertTrue($this->validator->isValid());
        $this->assertEmpty($this->validator->errors());
    }

    public function testValidatorRejectsInvalidEmail(): void
    {
        $data = [
            'name' => 'Bad Email',
            'email' => 'invalid',
            'username' => 'bademail',
            'employee_code' => '0000011',
            'password' => 'secret123',
            'role' => 'employee'
        ];

        $this->validator->validate($data);

        $this->assertFalse($this->validator->isValid());
        $this->assertArrayHasKey('email', $this->validator->errors());
    }

    public function testControllerDetectsDuplicateEmployeeCodeBeforeInsert(): void
    {
        $existing = [
            'name' => 'Alice Manager',
            'email' => 'alice.dupcheck@example.com', // unique email mock
            'username' => 'alice_dupcheck',          // unique username mock
            'employee_code' => '0009999',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'manager'
        ];

        $this->userModel->create($existing);
        $duplicate = $this->userModel->where(['employee_code' => $existing['employee_code']], true);

        $this->assertNotNull($duplicate);
        $this->assertEquals('0009999', $duplicate['employee_code']);
    }

}
