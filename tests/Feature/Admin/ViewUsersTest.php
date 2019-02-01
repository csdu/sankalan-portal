<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class ViewUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_all_Users()
    {
        $users = create(User::class, 5);
        $admins = create(User::class, 2, ['is_admin' => true]);

        $this->withoutExceptionHandling()->signIn($admins[0]);

        $resultUsers = $this->get(route('users.index'))->viewData('users');

        $this->assertCount(5, $resultUsers);
    }
}
