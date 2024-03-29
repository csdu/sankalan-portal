<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function signIn($user = null)
    {
        $this->be($user ?? create(User::class));

        return $this;
    }

    public function signInAdmin($admin = null)
    {
        $this->be($admin ?? create(User::class, 1, ['is_admin' => true]));

        return $this;
    }
}
