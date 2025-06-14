<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
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
