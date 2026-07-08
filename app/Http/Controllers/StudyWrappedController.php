<?php

namespace App\Http\Controllers;

use App\Models\StudyWrapped;
use App\Models\RevisionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudyWrappedController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $year = $request->integer('year', now()->year);

        if ($request->boolean('regenerate')) {
            $wrapped = StudyWrapped::generateForUser($user, $year);
        } else {
            $wrapped = StudyWrapped::where('user_id', $user->id)
                ->where('year', $year)
                ->first();

            if (!$wrapped) {
                $wrapped = StudyWrapped::generateForUser($user, $year);
            }
        }

        // Per-subject session stats for the year
        $perSubjectMinutes = $user->subjects()
            ->leftJoin('revision_sessions', function ($join) use ($year) {
                $join->on('subjects.id', '=', 'revision_sessions.subject_id')
                     ->whereYear('revision_sessions.date', '=', $year);
            })
            ->selectRaw('subjects.name, COALESCE(SUM(revision_sessions.duration_minutes), 0) as total_minutes')
            ->groupBy('subjects.id', 'subjects.name')
            ->orderByDesc('total_minutes')
            ->get()
            ->keyBy('name');

        // Overall performance — weighted average percentage across all subjects
        $overallPerformance = DB::table('marks')
            ->join('subjects', 'marks.subject_id', '=', 'subjects.id')
            ->where('subjects.user_id', $user->id)
            ->whereYear('marks.date', $year)
            ->selectRaw('AVG(marks.score * 100.0 / marks.max_score) as avg_pct')
            ->value('avg_pct');

        // Session-level stats for the year
        $sessionStats = RevisionSession::whereIn('subject_id', $user->subjects()->pluck('id'))
            ->whereYear('date', $year)
            ->selectRaw('COUNT(*) as count, COALESCE(AVG(duration_minutes), 0) as avg_length')
            ->first();

        $sessionCount = $sessionStats ? (int) $sessionStats->count : 0;
        $avgSessionLength = $sessionStats ? round((float) $sessionStats->avg_length, 1) : 0;

        return view('study-wrapped', compact(
            'wrapped', 'year', 'perSubjectMinutes', 'overallPerformance',
            'sessionCount', 'avgSessionLength'
        ));
    }
}
