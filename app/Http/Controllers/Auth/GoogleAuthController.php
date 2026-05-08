<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    public function handleGoogleToken(Request $request)
    {
        $request->validate(['token' => 'required|string']); //This helps to make sure that the data that comes to the server is the expected form of data
    }
}
