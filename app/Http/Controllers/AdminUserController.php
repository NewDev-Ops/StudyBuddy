<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $users = User::when($search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->with('university')
            ->orderBy('role', 'desc')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function promote(User $user)
    {
        $user->update(['role' => 'admin']);

        return redirect()->route('admin.users')
            ->with('success', "{$user->name} has been promoted to admin.");
    }

    public function demote(User $user)
    {
        $adminCount = User::where('role', 'admin')->count();

        if ($adminCount <= 1) {
            return redirect()->route('admin.users')
                ->with('error', 'This is the last remaining admin. Promote another user to admin before removing this role.');
        }

        $user->update(['role' => 'student']);

        return redirect()->route('admin.users')
            ->with('success', "{$user->name} has been demoted to student.");
    }
}
