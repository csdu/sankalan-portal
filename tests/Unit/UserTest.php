<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_be_admin() 
    {
        $defaultUser = create(User::class);
        $adminUser = create(User::class, 1, ['is_admin' => true]);

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($defaultUser->isAdmin());
    }
}
