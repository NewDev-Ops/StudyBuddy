<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Mark;
use App\Models\Resource;
use App\Models\RevisionSession;
use App\Models\StudyWrapped;
use App\Models\Subject;
use App\Models\University;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function student()
    {
        $user = Auth::user();
        $year = now()->year;

        $wrapped = StudyWrapped::where('user_id', $user->id)
            ->where('year', $year)
            ->first();

        $subjects = $user->subjects()->orderBy('name')->get();

        $subjectBreakdown = $subjects->map(function ($subject) use ($year) {
            $sessionCount = RevisionSession::where('subject_id', $subject->id)
                ->whereYear('date', $year)->count();

            $totalMinutes = RevisionSession::where('subject_id', $subject->id)
                ->whereYear('date', $year)->sum('duration_minutes');

            $markCount = Mark::where('subject_id', $subject->id)
                ->whereYear('date', $year)->count();

            $avgPercentage = Mark::where('subject_id', $subject->id)
                ->whereYear('date', $year)
                ->selectRaw('AVG(score * 100.0 / max_score) as avg_pct')
                ->value('avg_pct');

            $avgPercentage = $avgPercentage ? round((float) $avgPercentage, 1) : null;

            $band = $avgPercentage === null ? 'No Data'
                : ($avgPercentage >= 80 ? 'Excellent'
                    : ($avgPercentage >= 70 ? 'Good'
                        : ($avgPercentage >= 50 ? 'Needs Attention'
                            : 'At Risk')));

            return (object) [
                'name' => $subject->name,
                'session_count' => $sessionCount,
                'total_minutes' => $totalMinutes,
                'mark_count' => $markCount,
                'avg_percentage' => $avgPercentage,
                'band' => $band,
            ];
        });

        $sessions = RevisionSession::whereIn('subject_id', $subjects->pluck('id'))
            ->whereYear('date', $year)
            ->with('subject')
            ->latest('date')
            ->latest('created_at')
            ->get();

        $totalSessions = $sessions->count();
        $displaySessions = $sessions->take(20);

        $marks = Mark::whereIn('subject_id', $subjects->pluck('id'))
            ->whereYear('date', $year)
            ->with('subject')
            ->latest('date')
            ->latest('created_at')
            ->get();

        $totalMarks = $marks->count();
        $displayMarks = $marks->take(20);

        $filename = 'revisor-report-' . $year . '-' . preg_replace('/[^a-z0-9]/i', '_', $user->name) . '.pdf';

        $pdf = Pdf::loadView('reports.student', compact(
            'user', 'year', 'wrapped', 'subjectBreakdown',
            'displaySessions', 'totalSessions', 'displayMarks', 'totalMarks'
        ));

        return $pdf->download($filename);
    }

    public function overview()
    {
        $admin = Auth::user();
        $date = now()->format('Y-m-d');

        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $optedIn = User::where('role', 'student')->where('is_opted_in', true)->count();
        $optedInPct = $totalStudents > 0 ? round($optedIn / $totalStudents * 100, 1) : 0;
        $onboarded = User::where('role', 'student')->get()->filter(fn($u) => $u->hasCompletedOnboarding())->count();
        $notOnboarded = $totalStudents - $onboarded;
        $universitiesRepresented = User::whereNotNull('university_id')->distinct('university_id')->count('university_id');

        $totalSubjects = Subject::count();
        $totalSessions = RevisionSession::count();
        $totalMinutes = RevisionSession::sum('duration_minutes');
        $totalHours = round($totalMinutes / 60, 1);
        $totalMarks = Mark::count();
        $avgMarksPerStudent = $totalStudents > 0 ? round($totalMarks / $totalStudents, 1) : 0;

        $topSubjects = Subject::select('normalized_name', DB::raw('COUNT(DISTINCT user_id) as student_count'))
            ->whereNotNull('normalized_name')
            ->groupBy('normalized_name')
            ->orderByDesc('student_count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $avgPct = Mark::join('subjects', 'marks.subject_id', '=', 'subjects.id')
                    ->where('subjects.normalized_name', $item->normalized_name)
                    ->selectRaw('AVG(marks.score * 100.0 / marks.max_score) as avg_pct')
                    ->value('avg_pct');
                $item->avg_percentage = $avgPct ? round((float) $avgPct, 1) : null;
                return $item;
            });

        $universities = University::withCount('users')->orderBy('name')->get();

        $totalResources = Resource::count();
        $topTags = Resource::select('subject_tag', DB::raw('COUNT(*) as count'))
            ->groupBy('subject_tag')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $filename = 'revisor-admin-overview-' . $date . '.pdf';

        $pdf = Pdf::loadView('reports.admin-overview', compact(
            'admin', 'date',
            'totalUsers', 'totalStudents', 'totalAdmins',
            'optedIn', 'optedInPct', 'onboarded', 'notOnboarded',
            'universitiesRepresented',
            'totalSubjects', 'totalSessions', 'totalHours', 'totalMarks',
            'avgMarksPerStudent',
            'topSubjects', 'universities', 'totalResources', 'topTags',
        ));

        return $pdf->download($filename);
    }

    public function students()
    {
        $date = now()->format('Y-m-d');

        $students = User::where('role', 'student')
            ->with('university')
            ->orderBy('name')
            ->get()
            ->map(function ($student) {
                $subjectCount = $student->subjects()->count();

                $totalMinutes = RevisionSession::whereIn('subject_id', $student->subjects()->pluck('id'))
                    ->sum('duration_minutes');
                $totalHours = round($totalMinutes / 60, 1);

                $markCount = Mark::whereIn('subject_id', $student->subjects()->pluck('id'))->count();

                $avgPct = Mark::whereIn('subject_id', $student->subjects()->pluck('id'))
                    ->selectRaw('AVG(score * 100.0 / max_score) as avg_pct')
                    ->value('avg_pct');
                $avgPct = $avgPct ? round((float) $avgPct, 1) : null;

                $lastSession = RevisionSession::whereIn('subject_id', $student->subjects()->pluck('id'))
                    ->latest('date')->value('date');
                $lastMark = Mark::whereIn('subject_id', $student->subjects()->pluck('id'))
                    ->latest('date')->value('date');

                $lastActive = 'Never';
                if ($lastSession && $lastMark) {
                    $lastActive = max($lastSession, $lastMark)->format('M d, Y');
                } elseif ($lastSession) {
                    $lastActive = $lastSession->format('M d, Y');
                } elseif ($lastMark) {
                    $lastActive = $lastMark->format('M d, Y');
                }

                return (object) [
                    'name' => $student->name,
                    'university' => $student->university?->name ?? 'Not set',
                    'subject_count' => $subjectCount,
                    'total_hours' => $totalHours,
                    'mark_count' => $markCount,
                    'avg_pct' => $avgPct,
                    'peer_status' => $student->is_opted_in ? 'Visible' : 'Hidden',
                    'last_active' => $lastActive,
                ];
            })
            ->sortByDesc('last_active')
            ->values();

        $summary = (object) [
            'total_students' => $students->count(),
            'avg_subjects' => round($students->avg('subject_count'), 1),
            'total_hours' => round($students->sum('total_hours'), 1),
            'total_marks' => $students->sum('mark_count'),
            'avg_pct' => $students->whereNotNull('avg_pct')->avg('avg_pct'),
            'visible_count' => $students->where('peer_status', 'Visible')->count(),
        ];
        $summary->avg_pct = $summary->avg_pct ? round($summary->avg_pct, 1) : null;

        $filename = 'revisor-admin-students-' . $date . '.pdf';

        $pdf = Pdf::loadView('reports.admin-students', compact('students', 'summary', 'date'));

        return $pdf->download($filename);
    }

    public function feedback()
    {
        $date = now()->format('Y-m-d');

        $total = Feedback::count();
        $unread = Feedback::where('is_read', false)->count();

        $categoryBreakdown = Feedback::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
            ->get()
            ->map(function ($item) use ($total) {
                $item->percentage = $total > 0 ? round($item->count / $total * 100, 1) : 0;
                return $item;
            });

        $allFeedback = Feedback::latest()->get()->map(function ($item) {
            $item->truncated_message = strlen($item->message) > 150
                ? substr($item->message, 0, 150) . '...'
                : $item->message;
            return $item;
        });

        $filename = 'revisor-admin-feedback-' . $date . '.pdf';

        $pdf = Pdf::loadView('reports.admin-feedback', compact(
            'date', 'total', 'unread', 'categoryBreakdown', 'allFeedback'
        ));

        return $pdf->download($filename);
    }
}
