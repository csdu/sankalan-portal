<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_be_admin()
    {
        $defaultUser = create(User::class);
        $adminUser = create(User::class, 1, ['is_admin' => true]);

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($defaultUser->isAdmin());
    }
}
