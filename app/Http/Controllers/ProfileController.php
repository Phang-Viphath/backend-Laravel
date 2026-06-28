<?php

namespace App\Http\Controllers;

use App\Services\S3Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getProfile()
    {
        $user = Auth::user();
        $user->load('profile');
        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
        ]);

        $user->name = $request->name;
        $user->save();

        $user->profile()->updateOrCreate([], [
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->load('profile')
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        $s3 = app(S3Service::class);

        if ($user->profile && $user->profile->image) {
            $s3->deleteImage($user->profile->image);
        }

        $upload = $s3->uploadImage($request->file('image')->getRealPath(), 'profiles');

        $user->profile()->updateOrCreate([], [
            'image' => $upload['url'],
        ]);

        return response()->json([
            'message' => 'Profile image updated successfully',
            'user' => $user->load('profile')
        ]);
    }

    public function removeImage()
    {
        $user = Auth::user();

        if ($user->profile && $user->profile->image) {
            $s3 = app(S3Service::class);
            $s3->deleteImage($user->profile->image);

            $user->profile->image = null;
            $user->profile->save();
        }

        return response()->json([
            'message' => 'Profile image removed successfully',
            'user' => $user->load('profile')
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
}