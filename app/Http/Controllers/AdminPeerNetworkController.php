<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminPeerNetworkController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')
            ->where('is_opted_in', true)
            ->with('university')
            ->orderBy('name')
            ->get();

        return view('admin.peer-network.index', compact('students'));
    }

    public function remove(User $user)
    {
        $user->update(['is_opted_in' => false]);

        return redirect()->route('admin.peer-network')
            ->with('success', "{$user->name} has been removed from the peer network. They can opt back in later.");
    }
}
