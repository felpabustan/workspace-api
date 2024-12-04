<?php
namespace App\Http\Controllers\Space;

use App\Models\Space;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SpaceController extends Controller
{
    public function index()
    {
        $spaces = Space::all();
        return response()->json($spaces);
    }

    public function show($id)
    {
        $today = now()->startOfDay();
    
        $space = Space::with(['bookings' => function ($query) use ($today) {
                            $query->where('start_time', '>=', $today)
                                  ->select('id', 'space_id', 'start_time', 'end_time')
                                  ->orderBy('start_time', 'asc');
                        }])
                        ->findOrFail($id);
    
        return response()->json($space);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validate image
        ]);

        $space = new Space($request->all());

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $space->image = Storage::url($imagePath);
        }

        $space->save();

        return response()->json($space, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255',
            'rate_hourly' => 'nullable|numeric',
            'rate_daily' => 'nullable|numeric',
            'rate_weekly' => 'nullable|numeric',
            'rate_monthly' => 'nullable|numeric',
            'availability' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $space = Space::findOrFail($id);
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('spaces', 'public');
            $space->image = $imagePath;
        }
  
        $space->update($request->except('image'));
    
        return response()->json($space);
    }
    
    public function destroy($id)
    {
        $space = Space::findOrFail($id);
        $space->delete();
        return response()->json(['message' => 'Space deleted successfully'], 204);
    }
}
