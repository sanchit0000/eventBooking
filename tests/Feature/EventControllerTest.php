<?php
namespace Tests\Feature;

use App\Http\Controllers\Api\EventController;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_event()
    {
        $data = [
            'title' => 'Test Event',
            'start_time' => now()->addDay()->toDateTimeString(),
            'end_time' => now()->addDays(2)->toDateTimeString(),
            'country' => 'US',
            'capacity' => 100
        ];

        $request = CreateEventRequest::create('/api/v1/events', 'POST', $data);
        $request->setContainer(app())->validateResolved();

        $controller = new EventController();
        $response = $controller->store($request);

        $this->assertInstanceOf(EventResource::class, $response);
    }

    public function test_update_modifies_event()
    {
        $event = Event::factory()->create();
        $data = ['capacity' => 200];
        
        $request = UpdateEventRequest::create("/api/v1/events/{$event->id}", 'PUT', $data);
        $request->setContainer(app())->validateResolved();

        $controller = new EventController();
        $response = $controller->update($request, $event);

        $this->assertInstanceOf(EventResource::class, $response);
        $this->assertEquals(200, $event->fresh()->capacity);
    }
}