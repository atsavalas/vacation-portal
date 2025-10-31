<?php

use PHPUnit\Framework\TestCase;
use App\Models\User;

final class UserTest extends TestCase
{
    protected User $userModel;

    protected function setUp(): void
    {
        $this->userModel = new User();
    }

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
}
