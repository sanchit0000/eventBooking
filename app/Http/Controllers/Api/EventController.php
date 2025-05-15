<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Traits\HandlesApiErrors;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    use HandlesApiErrors;

    public function index()
    {
        try {
            return EventResource::collection(Event::withCount('bookings')->paginate(10));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function store(CreateEventRequest $request): JsonResponse|EventResource
    {
        try {
            return new EventResource(Event::create($request->validated()));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function show(Event $event): JsonResponse|EventResource
    {
        try {
            return new EventResource($event->load('bookings'));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function update(UpdateEventRequest $request, Event $event): JsonResponse|EventResource
    {
        try {
            $event->update($request->validated());
            return new EventResource($event);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function destroy(Event $event): JsonResponse
    {
        try {
            $event->delete();
            return response()->json(['message' => 'Event deleted successfully']);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
}