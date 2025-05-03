# Event Booking API (Laravel)

A RESTful API for managing events, attendees, and bookings, built with Laravel.

## Features

- **Events**: Create, update, delete, and list events.
- **Attendees**: Register and manage attendees.
- **Bookings**: Book events with duplicate and capacity protection.

---

## Endpoints

### Events (Protected)

- `GET /api/v1/events` – List events
- `POST /api/v1/events` – Create event
- `PUT /api/v1/events/{id}` – Update event
- `DELETE /api/v1/events/{id}` – Delete event

### Attendees (Public)

- `POST /api/v1/attendees` – Register attendee

### Bookings (Public)

- `POST /api/v1/events/{event_id}/book` – Book an event

---

## Authentication & Authorization (Design Overview)

- All event management endpoints (create, update, delete) are protected using `auth:sanctum` middleware.
- Attendees can register and book events without authentication.
- Laravel Sanctum is recommended for token-based authentication for managing events.

Example route structure:

```php
Route::prefix('v1')->group(function () {
    Route::post('/attendees', [AttendeeController::class, 'store']);
    Route::post('/events/{event}/book', [BookingController::class, 'book']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('events', EventController::class);
    });
});
```

---

## Setup Instructions

1. Clone the repository:

   ```bash
   git clone https://github.com/your-username/event-booking-api.git
   cd event-booking-api
   ```

2. Install dependencies:

   ```bash
   composer install
   ```

3. Copy `.env` file and generate app key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Set up database credentials in `.env`

5. Run migrations:

   ```bash
   php artisan migrate
   ```

6. Start local server:
   ```bash
   php artisan serve
   ```

---

## Running Tests

```bash
php artisan test
```

---

## Postman Collection

Import the `Event Booking.postman_collection.json` file into Postman for quick testing of all endpoints.

---

## License

MIT
