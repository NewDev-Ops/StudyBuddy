<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Laravel\Firebase\Facades\Firebase;

class GoogleAuthController extends Controller
{
    public function handleGoogleToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        try {
            $verifiedToken = Firebase::auth()->verifyIdToken($request->token);
            $uid = $verifiedToken->claims()->get('sub');
            $email = $verifiedToken->claims()->get('email');
            $name = $verifiedToken->claims()->get('name');

            $user = User::updateOrCreate(
                ['google_id' => $uid],
                ['name' => $name, 'email' => $email]
            );

            Auth::login($user);

            // Route based on role
            if ($user->role === 'admin') {
                return response()->json([
                    'success' => true,
                    'redirect' => '/admin/dashboard'
                ]);
            }

            return response()->json([
                'success' => true,
                'redirect' => '/dashboard'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}