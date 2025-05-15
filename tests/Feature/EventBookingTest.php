<?php

namespace Tests\Feature;

use App\Models\Attendee;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventBookingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_create_event()
{
    $response = $this->postJson('/api/v1/events', [
        'title' => 'Test Event',
        'start_time' => now()->addDay()->toDateTimeString(),
        'end_time' => now()->addDays(2)->toDateTimeString(),
        'country' => 'US',
        'capacity' => 100
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['data' => ['id']]);
}

public function updates_event_capacity()
{
    $event = Event::factory()->create(['capacity' => 100]);
    
    $response = $this->putJson("/api/v1/events/{$event->id}", [
        'capacity' => 150
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.capacity', 150);
}

    #[Test]
    public function prevents_overbooking()
    {
        $event = Event::factory()->create(['capacity' => 1]);
        $attendees = Attendee::factory(2)->create();

        $this->postJson("/api/v1/events/{$event->id}/book", [
            'attendee_id' => $attendees[0]->id
        ]);

        $response = $this->postJson("/api/v1/events/{$event->id}/book", [
            'attendee_id' => $attendees[1]->id
        ]);

        $response->assertStatus(403);
    }
}