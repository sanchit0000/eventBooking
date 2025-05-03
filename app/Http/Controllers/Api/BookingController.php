<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Attendee;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function book(Request $request, Event $event)
    {
        $request->validate([
            'attendee_id' => 'required|exists:attendees,id'
        ]);

        $attendeeId = $request->input('attendee_id');

        // Prevent duplicate bookings
        if (Booking::where('event_id', $event->id)->where('attendee_id', $attendeeId)->exists()) {
            return response()->json(['message' => 'Already booked for this event'], 409);
        }

        // Prevent overbooking
        $bookingCount = Booking::where('event_id', $event->id)->count();
        if ($bookingCount >= $event->capacity) {
            return response()->json(['message' => 'Event is fully booked'], 403);
        }

        $booking = Booking::create([
            'event_id' => $event->id,
            'attendee_id' => $attendeeId
        ]);

        return response()->json(['message' => 'Booking successful', 'booking' => $booking], 201);
    }
}
