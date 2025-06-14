<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_access_admins_dashboard()
    {
        $this->withoutExceptionHandling()->signInAdmin();

        $this->get(route('admin.dashboard'))
            ->assertSuccessful()
            ->assertViewIs('admin.dashboard');
    }

    #[Test]
    public function normal_user_cannot_access_admins_dashboard()
    {
        $this->withExceptionHandling()->signIn();

        $this->get(route('admin.dashboard'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
