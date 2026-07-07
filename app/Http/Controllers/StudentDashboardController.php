<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Resource;
use App\Models\RevisionSession;
use App\Services\PeerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $suggestedSubject = $user->suggestedSubject();
        $suggestionBreakdown = $user->suggestionBreakdown;

        $recommendedResources = collect();
        if ($suggestedSubject && $suggestedSubject->normalized_name) {
            $matches = Resource::where('normalized_subject_tag', $suggestedSubject->normalized_name)->get();
            $recommendedResources = $matches->count() > 3
                ? $matches->random(3)
                : $matches;
        }

        $peerSuggestions = null;
        $peerComparisonMode = null;
        if ($user->is_opted_in && $suggestedSubject && $suggestedSubject->normalized_name) {
            $peerService = app(PeerService::class);
            $thresholds = $peerService->resolveThresholds($user, $suggestedSubject->normalized_name);
            $threshold = $thresholds['threshold'];
            $comparison = $thresholds['comparison'];
            $peerComparisonMode = $thresholds['mode'];

            $rows = DB::select("
                SELECT
                    u.id,
                    u.name AS student_name,
                    un.name AS university_name,
                    s.name AS subject_name,
                    ROUND(AVG(m.score * 100.0 / m.max_score), 1) AS avg_percentage
                FROM users u
                INNER JOIN subjects s ON s.user_id = u.id AND s.normalized_name = ?
                INNER JOIN marks m ON m.subject_id = s.id
                LEFT JOIN universities un ON un.id = u.university_id
                WHERE u.is_opted_in = 1
                  AND u.id != ?
                GROUP BY u.id, u.name, un.name, s.name
                HAVING avg_percentage $comparison ?
                ORDER BY
                    CASE WHEN u.university_id IS NOT NULL AND u.university_id = ? THEN 0 ELSE 1 END,
                    avg_percentage DESC,
                    u.name ASC
                LIMIT 3
            ", [
                $suggestedSubject->normalized_name,
                $user->id,
                $threshold,
                $user->university_id,
            ]);

            $peerSuggestions = collect($rows)->map(fn ($r) => (object) [
                'id' => $r->id,
                'name' => $r->student_name,
                'university_name' => $r->university_name,
                'subject_name' => $r->subject_name,
            ]);
        }

        return view('dashboard', compact('subjects', 'university', 'recentSessions', 'recentMarks', 'suggestedSubject', 'suggestionBreakdown', 'recommendedResources', 'peerSuggestions', 'peerComparisonMode'));
    }
}