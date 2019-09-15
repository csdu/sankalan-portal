<?php

namespace Tests\Unit;

use App\Jobs\EventsJsonImport;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsJsonImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function events_are_read_from_json_and_imported_to_database()
    {
        $jsonFilepath = base_path('tests/stubs/events.json');

        EventsJsonImport::dispatch($jsonFilepath);

        $allEvents = Event::with('quizzes.questions.choices')->get();

        $this->assertCount(1, $allEvents);
        $this->assertCount(1, $allEvents->first()->quizzes);
        $this->assertCount(3, $allEvents->first()->quizzes->first()->questions);
        $this->assertCount(4, $allEvents->first()->quizzes->first()->instructions);
    }
}
