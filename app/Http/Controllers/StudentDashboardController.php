<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\RevisionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $subjects = $user->subjects()->get();
        $university = $user->university;

        $subjectIds = $subjects->pluck('id');
        $recentSessions = RevisionSession::whereIn('subject_id', $subjectIds)
            ->with('subject')
            ->latest('date')
            ->latest('created_at')
            ->limit(10)
            ->get();

        $recentMarks = Mark::whereIn('subject_id', $subjectIds)
            ->with('subject')
            ->latest('date')
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('dashboard', compact('subjects', 'university', 'recentSessions', 'recentMarks'));
    }
}