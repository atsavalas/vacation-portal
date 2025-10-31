<?php
use PHPUnit\Framework\TestCase;
use App\Helpers\Auth;

final class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = []; // reset session before each test
    }

    public function testLoginStoresUserInSession(): void
    {
        $fakeUser = [
            'id' => 1,
            'name' => 'Alice Banks',
            'email' => 'alice@vacation-portal.test',
            'role' => 'manager'
        ];

        Auth::login($fakeUser);

        $this->assertArrayHasKey('user', $_SESSION);
        $this->assertSame('manager', $_SESSION['user']['role']);
    }

    public function testLogoutClearsSession(): void
    {
        $_SESSION['user'] = ['id' => 1, 'role' => 'manager'];
        Auth::logout();

        $this->assertArrayNotHasKey('user', $_SESSION);
    }
}
