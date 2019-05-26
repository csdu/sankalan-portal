<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registered_user_is_not_an_admin()
    {
        $user = make(User::class);
        $this->withExceptionHandling();

        $response = $this->post(
            route('register'),
            $user->toArray() + [
                'password' => 'secret',
                'password_confirmation' => 'secret',
            ]
        );

        $response->assertRedirect(route('dashboard'));

        $this->assertTrue(Auth::check());
        $this->assertFalse(Auth::user()->isAdmin());
    }
}
