<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\BookingController;

Route::prefix('v1')->group(function () {

    // Event Management (requires auth in real-world scenario)
    Route::apiResource('events', EventController::class);

    // Attendee Registration
    Route::apiResource('attendees', AttendeeController::class)->only(['store', 'index']);

    // Bookings
    Route::post('/events/{event}/book', [BookingController::class, 'book']);

});
