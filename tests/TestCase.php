<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

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
