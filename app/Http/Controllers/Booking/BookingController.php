<?php

namespace App\Http\Controllers\Booking;

use App\Models\Space;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{

    public function index()
    {
        return response()->json(Booking::all());
    }

    public function show($id)
    {
        $booking = Booking::with('space:id,name,description,rate_hourly,rate_daily,rate_weekly,rate_monthly')
                          ->findOrFail($id);

        return response()->json($booking);
    }

    public function store(Request $request)
    {
        $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'rate_type' => 'required|in:hourly,daily,weekly,monthly',
            'duration' => 'required|integer|min:1',
        ]);

        $space = Space::findOrFail($request->space_id);
        $booking = new Booking($request->all());

        $booking->price = $booking->calculatePrice();
        $booking->save();

        return response()->json(['booking' => $booking], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'space_id' => 'sometimes|required|exists:spaces,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'rate_type' => 'sometimes|required|in:hourly,daily,weekly,monthly',
            'duration' => 'sometimes|required|integer|min:1',
        ]);
    
        $booking = Booking::findOrFail($id);
        
        $booking->fill($request->all());
    
        if ($request->has('start_time')) {
            $booking->start_time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $request->start_time)
                ->format('Y-m-d H:i:s');
        }
    
        if ($request->has('end_time')) {
            $booking->end_time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $request->end_time)
                ->format('Y-m-d H:i:s');
        }

        $booking->price = $booking->calculatePrice();
        $booking->save();
    
        return response()->json(['booking' => $booking], 200);
    }
    
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully'], 204);
    }

    public function isSpaceAvailable($spaceId, $startTime, $endTime)
    {
        return !Booking::where('space_id', $spaceId)
                        ->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime)
                        ->exists();
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'space_id' => 'required|integer|exists:spaces,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $spaceId = $request->input('space_id');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        $bookings = Booking::where('space_id', $spaceId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                      });
            })
            ->get();

        $isAvailable = $bookings->isEmpty();

        return response()->json(['available' => $isAvailable]);
    }
}
