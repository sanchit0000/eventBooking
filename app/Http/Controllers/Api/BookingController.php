<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookEventRequest;
use App\Http\Resources\BookingResource;
use App\Models\Event;
use App\Services\BookingService;
use App\Traits\HandlesApiErrors;

class BookingController extends Controller
{
    use HandlesApiErrors;

    public function __construct(
        private BookingService $bookingService
    ) {}

    public function book(BookEventRequest $request, Event $event)
    {
        try {
            $booking = $this->bookingService->bookEvent(
                $event,
                $request->validated()['attendee_id']
            );

            return response()->json([
                'message' => 'Booking successful',
                'booking' => new BookingResource($booking)
            ], 201);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
}
