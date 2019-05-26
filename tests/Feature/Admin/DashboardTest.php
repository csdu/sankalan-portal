<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_access_admins_dashboard()
    {
        $this->withoutExceptionHandling()->signInAdmin();

        $this->get(route('admin.dashboard'))
            ->assertSuccessful()
            ->assertViewIs('admin.dashboard');
    }

    /** @test */
    public function normal_user_cannot_access_admins_dashboard()
    {
        $this->withExceptionHandling()->signIn();

        $this->get(route('admin.dashboard'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
