<?php

namespace App\Http\Controllers;
use App\Models\Room;
use App\Services\S3Service;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['reservations' => function($query) {
            $query->select('id', 'room_id', 'check_in', 'check_out', 'status')
                  ->whereIn('status', ['Pending', 'Confirmed', 'Checked In'])
                  ->where('check_out', '>=', \Carbon\Carbon::today()->toDateString())
                  ->orderBy('check_in', 'asc');
        }])->get();
        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|unique:rooms,number',
            'type' => 'required|string',
            'floor' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,cleaning,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $s3 = app(S3Service::class);
            $upload = $s3->uploadImage($request->file('image')->getRealPath(), 'rooms');
            $imageUrl = $upload['url'];
        }   

        $room = Room::create([
            'number' => $request->number,
            'type' => $request->type,
            'floor' => $request->floor,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'status' => $request->status,
            'image' => $imageUrl
        ]);
        return response()->json($room, 201);
    }

    public function show($id)
    {
        $room = Room::find($id);
        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }
        return response()->json($room);
    }

    public function update(Request $request, $id)
    {
        $room = Room::find($id);
        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $request->validate([
            'number' => 'required|string|unique:rooms,number,' . $id,
            'type' => 'required|string',
            'floor' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'in:available,occupied,cleaning,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $s3 = app(S3Service::class);
            if ($room->image) {
                $s3->deleteImage($room->image);
            }
            $upload = $s3->uploadImage($request->file('image')->getRealPath(), 'rooms');
            $room->image = $upload['url'];
        }

        $room->update([
            'number' => $request->number ?? $room->number,
            'type' => $request->type ?? $room->type,
            'floor' => $request->floor ?? $room->floor,
            'capacity' => $request->capacity ?? $room->capacity,
            'price' => $request->price ?? $room->price,
            'status' => $request->status ?? $room->status,
        ]);
        return response()->json($room);
    }

    public function destroy($id)
    {
        $room = Room::find($id);
        if (!$room) { 
            return response()->json(['error' => 'Room not found'], 404);
        }

        if ($room->image) {
            $s3 = app(S3Service::class);
            $s3->deleteImage($room->image);
        }

        $room->delete();
        return response()->json(['message' => 'Room deleted successfully']);
    }
}