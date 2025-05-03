<?php

namespace Tests\Feature;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_attendee_can_book_an_event()
    {
        $event = Event::factory()->create(['capacity' => 2]);
        $attendee = Attendee::factory()->create();

        $response = $this->postJson("/api/v1/events/{$event->id}/book", [
            'attendee_id' => $attendee->id
        ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Booking successful']);

        $this->assertDatabaseHas('bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ]);
    }

    /** @test */
    public function it_prevents_duplicate_bookings()
    {
        $event = Event::factory()->create(['capacity' => 2]);
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

    /** @test */
    public function it_blocks_booking_when_capacity_is_full()
    {
        $event = Event::factory()->create(['capacity' => 1]);
        $attendee1 = Attendee::factory()->create();
        $attendee2 = Attendee::factory()->create();

        Booking::create([
            'event_id' => $event->id,
            'attendee_id' => $attendee1->id
        ]);

        $response = $this->postJson("/api/v1/events/{$event->id}/book", [
            'attendee_id' => $attendee2->id
        ]);

        $response->assertStatus(403);
    }
}
