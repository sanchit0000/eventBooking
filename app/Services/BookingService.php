<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Event;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingService
{
    public function bookEvent(Event $event, int $attendeeId)
    {
        if (Booking::where('event_id', $event->id)
            ->where('attendee_id', $attendeeId)
            ->exists()) {
            throw new HttpException(409, 'Already booked for this event');
        }

        if ($event->bookings()->count() >= $event->capacity) {
            throw new HttpException(403, 'Event is fully booked');
        }

        return Booking::create([
            'event_id' => $event->id,
            'attendee_id' => $attendeeId
        ]);
    }
}