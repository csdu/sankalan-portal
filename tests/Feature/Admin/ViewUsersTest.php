<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ViewUsersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function list_all_Users()
    {
        $users = create(User::class, 25);
        $admins = create(User::class, 2, ['is_admin' => true]);

        $this->withoutExceptionHandling()->signIn($admins[0]);

        $resultUsers = $this->get(route('admin.users.index'))->viewData('users');

        $this->assertInstanceOf(LengthAwarePaginator::class, $resultUsers);
        $this->assertCount(config('app.pagination.perPage'), $resultUsers);
        $this->assertEquals(25, $resultUsers->total());
    }
}
