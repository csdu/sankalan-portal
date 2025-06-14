<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventEditTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_sees_edit_form()
    {
        $event = create(Event::class, 1);

        $this->withoutExceptionHandling()->signInAdmin();
        $res = $this->get(route('admin.events.edit', $event));

        $res->assertOk();
    }

    #[Test]
    public function admin_can_edit_an_event()
    {
        $event = create(Event::class, 1);

        $this->withoutExceptionHandling()->signInAdmin();

        $data = [
            'title' => 'New title',
            'description' => 'changed desc',
            'rounds' => 5,
        ];

        $res = $this->patch(route('admin.events.update', $event), $data);
        $res->assertRedirect(route('admin.events.index'));
    }
}
