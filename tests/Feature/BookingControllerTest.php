<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Booking;
use App\Models\Space;
use App\Models\User;
use Carbon\Carbon;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_booking_successfully()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
    
        // Create a space
        $space = Space::factory()->create(['rate_hourly' => 10]);
    
        // Define the request data with formatted dates
        $data = [
            'space_id' => $space->id,
            'user_id' => $user->id,
            'start_time' => now()->format('Y-m-d H:i:s'),  // Format to MySQL-compatible datetime
            'end_time' => now()->addHours(2)->format('Y-m-d H:i:s'),  // Format to MySQL-compatible datetime
            'rate_type' => 'hourly',
            'duration' => 2,
            'price' => $space->rate_hourly * 2,
        ];
    
        // Send the request to store the booking
        $response = $this->postJson('/api/bookings', $data);
    
        // Assert that the booking was created in the database
        $this->assertDatabaseHas('bookings', [
            'space_id' => $space->id,
            'price' => $data['price'],
        ]);
    
        // Assert successful response
        $response->assertStatus(201);
    }

    public function test_store_booking_validation_error()
    {
        // Create a user and simulate authentication
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
    
        // Send an incomplete request
        $response = $this->postJson('/api/bookings', []);
    
        // Assert validation error
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['space_id', 'user_id', 'start_time', 'end_time', 'rate_type', 'duration']);
    }
}
