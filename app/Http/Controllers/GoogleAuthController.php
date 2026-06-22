<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Guests;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => bcrypt('google_' . $googleUser->id),
                ]);
            }

            Auth::login($user);

            return redirect('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google authentication failed');
        }
    }

    public function guestRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function guestCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $guest = Guests::where('google_id', $googleUser->id)->first();

            if (!$guest) {
                $guest = Guests::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'image' => $googleUser->avatar,
                ]);
            }

            // Redirect to frontend with guest data
            $frontendUrl = env('FRONTEND_URL', 'https://frontend-user-mu-lac.vercel.app');
            return redirect($frontendUrl . '/auth/google/callback?guest_id=' . $guest->id . '&name=' . urlencode($guest->name) . '&email=' . urlencode($guest->email) . '&image=' . urlencode($guest->image ?? ''));
        } catch (\Exception $e) {
            $frontendUrl = env('FRONTEND_URL', 'https://frontend-user-mu-lac.vercel.app');
            return redirect($frontendUrl . '/login?error=google_auth_failed');
        }
    }
}