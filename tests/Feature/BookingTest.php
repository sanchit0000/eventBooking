<?php

namespace Tests\Feature;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Booking;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_booking()
    {
        $event = Event::factory()->create(['capacity' => 2]);
        $attendee = Attendee::factory()->create();

        $response = $this->postJson("/api/v1/events/{$event->id}/book", [
            'attendee_id' => $attendee->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'booking']);
    }

    public function test_prevent_double_booking()
    {
        $event = Event::factory()->create();
        $attendee = Attendee::factory()->create();
        
        Booking::create([
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ]);

        $response = $this->postJson("/api/v1/events/{$event->id}/book", [
            'attendee_id' => $attendee->id
        ]);

        $response->assertStatus(409);
    }
}