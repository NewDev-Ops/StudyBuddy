<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $subjects = $user->subjects()->with('user')->get();
        $university = $user->university;

        return view('dashboard', compact('subjects', 'university'));
    }
}