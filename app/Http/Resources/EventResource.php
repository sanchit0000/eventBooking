<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'description' => $this->description,
        'start_time' => $this->start_time->toIso8601String(),
        'end_time' => $this->end_time->toIso8601String(),
        'country' => $this->country,
        'capacity' => $this->capacity,
        'available_seats' => $this->capacity - $this->bookings_count,
        'bookings' => BookingResource::collection($this->whenLoaded('bookings'))
    ];
}
}