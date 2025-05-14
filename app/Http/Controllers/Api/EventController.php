<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Traits\HandlesApiErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    use HandlesApiErrors;

    public function index()
    {
        try {
            $events = Event::withCount('bookings')->paginate(10);
            return EventResource::collection($events);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function store(CreateEventRequest $request)
    {
        try {
            $event = Event::create($request->validated());
            return new EventResource($event);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function show(Event $event)
    {
        try {
            return new EventResource($event->load('bookings'));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function update(Request $request, Event $event)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time',
                'country' => 'sometimes|string',
                'capacity' => 'sometimes|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $event->update($request->all());
            return new EventResource($event);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function destroy(Event $event)
    {
        try {
            $event->delete();
            return response()->json(['message' => 'Event deleted successfully']);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
}