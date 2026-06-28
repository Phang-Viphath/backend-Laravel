<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guests;
use App\Services\S3Service;
use Illuminate\Support\Facades\Hash;

class GuestsController extends Controller
{
    public function index()
    {
        $guest = Guests::all();
        return response()->json($guest);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:guests,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $s3 = app(S3Service::class);
            $upload = $s3->uploadImage($request->file('image')->getRealPath(), 'guests');
            $imageUrl = $upload['url'];
        }

        $guest = Guests::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'image' => $imageUrl,
        ]);

        return response()->json(['message' => 'Guest created successfully', 'guest' => $guest], 201);
    }

    public function show($id)
    {
        $guest = Guests::find($id);
        if (!$guest) {
            return response()->json(['message' => 'Guest not found'], 404);
        }
        return response()->json($guest);
    }

    public function loginByEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $guest = Guests::where('email', $validated['email'])->first();
        if (!$guest) {
            return response()->json(['message' => 'Guest not found'], 404);
        }
        if (!Hash::check($validated['password'], $guest->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'guest' => $guest
        ]);
    }

    public function update(Request $request, $id)
    {
        $guest = Guests::find($id);
        if (!$guest) {
            return response()->json(['message' => 'Guest not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|string|email|unique:guests,email,' . $id,
            'phone' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $s3 = app(S3Service::class);
            if ($guest->image) {
                $s3->deleteImage($guest->image);
            }
            $upload = $s3->uploadImage($request->file('image')->getRealPath(), 'guests');
            $guest->image = $upload['url'];
        }

        $guest->update(
            [
                'name' => $request->name ?? $guest->name,
                'email' => $request->email ?? $guest->email,
                'phone' => $request->phone ?? $guest->phone,
            ]
        );

        $guest->save();

        return response()->json(['message' => 'Guest updated successfully', 'guest' => $guest]);
    }

    public function destroy($id)
    {
        $guest = Guests::find($id);
        if (!$guest) {
            return response()->json(['message' => 'Guest not found'], 404);
        }

        if ($guest->image) {
            $s3 = app(S3Service::class);
            $s3->deleteImage($guest->image);
        }

        $guest->delete();

        return response()->json(['message' => 'Guest deleted successfully']);
    }
}
