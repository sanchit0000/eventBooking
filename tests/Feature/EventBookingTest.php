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
            'start_time' => now()->addDay(),
            'end_time' => now()->addDays(2),
            'country' => 'US',
            'capacity' => 100
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id']]);
    }

    #[Test]
    public function prevents_double_booking()
    {
        $event = Event::factory()->create();
        $attendee = Attendee::factory()->create();

        // First booking
        $this->postJson("/api/v1/events/{$event->id}/book", [
            'attendee_id' => $attendee->id
        ]);

        // Second booking
        $response = $this->postJson("/api/v1/events/{$event->id}/book", [
            'attendee_id' => $attendee->id
        ]);

        $response->assertStatus(409);
    }

    #[Test]
    public function prevents_overbooking()
    {
        $event = Event::factory()->create(['capacity' => 1]);
        $attendees = Attendee::factory(2)->create();

        // First booking
        $this->postJson("/api/v1/events/{$event->id}/book", [
            'attendee_id' => $attendees[0]->id
        ]);

        // Second booking
        $response = $this->postJson("/api/v1/events/{$event->id}/book", [
            'attendee_id' => $attendees[1]->id
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function updates_event_capacity()
    {
        $event = Event::factory()->create(['capacity' => 100]);
        $updateData = ['capacity' => 150];

        $response = $this->putJson("/api/v1/events/{$event->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('data.capacity', 150);
    }
}