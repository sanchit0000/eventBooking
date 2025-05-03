<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_event()
    {
        $event = Event::factory()->create([
            'capacity' => 50,
            'country' => 'USA',
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'capacity' => 50,
            'country' => 'USA'
        ]);
    }
}

