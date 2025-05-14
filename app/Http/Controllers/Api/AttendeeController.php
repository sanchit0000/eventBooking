<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAttendeeRequest;
use App\Models\Attendee;

class AttendeeController extends Controller
{
    public function index()
    {
        return response()->json(Attendee::all());
    }

    public function store(CreateAttendeeRequest $request)
    {
        $attendee = Attendee::create($request->validated());
        return response()->json($attendee, 201);
    }
}
