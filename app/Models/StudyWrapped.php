<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyWrapped extends Model
{
    protected $table = 'study_wrapped';

    protected $fillable = [
        'user_id', 'year', 'total_hours',
        'most_studied_subject', 'most_neglected_subject', 'highest_performing_subject',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'total_hours' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateForUser(User $user, int $year): self
    {
        $subjectCount = $user->subjects()->count();

        // Most studied: subject with highest SUM(duration_minutes)
        $mostStudied = $user->subjects()
            ->leftJoin('revision_sessions', function ($join) use ($year) {
                $join->on('subjects.id', '=', 'revision_sessions.subject_id')
                     ->whereYear('revision_sessions.date', '=', $year);
            })
            ->selectRaw('subjects.name, COALESCE(SUM(revision_sessions.duration_minutes), 0) as total_minutes')
            ->groupBy('subjects.id', 'subjects.name')
            ->orderByDesc('total_minutes')
            ->first();

        // Most neglected: subject with LOWEST SUM(duration_minutes) — 0 if no sessions
        $mostNeglected = $user->subjects()
            ->leftJoin('revision_sessions', function ($join) use ($year) {
                $join->on('subjects.id', '=', 'revision_sessions.subject_id')
                     ->whereYear('revision_sessions.date', '=', $year);
            })
            ->selectRaw('subjects.name, COALESCE(SUM(revision_sessions.duration_minutes), 0) as total_minutes')
            ->groupBy('subjects.id', 'subjects.name')
            ->orderBy('total_minutes')
            ->first();

        // Highest performing: subject with highest AVG(score/max_score)
        $highestPerforming = $user->subjects()
            ->join('marks', function ($join) use ($year) {
                $join->on('subjects.id', '=', 'marks.subject_id')
                     ->whereYear('marks.date', '=', $year);
            })
            ->selectRaw('subjects.name, AVG(marks.score * 100.0 / marks.max_score) as avg_pct')
            ->groupBy('subjects.id', 'subjects.name')
            ->orderByDesc('avg_pct')
            ->first();

        // Total hours
        $totalMinutes = $user->subjects()
            ->join('revision_sessions', function ($join) use ($year) {
                $join->on('subjects.id', '=', 'revision_sessions.subject_id')
                     ->whereYear('revision_sessions.date', '=', $year);
            })
            ->sum('revision_sessions.duration_minutes');

        $totalHours = round($totalMinutes / 60, 1);

        return static::updateOrCreate(
            ['user_id' => $user->id, 'year' => $year],
            [
                'most_studied_subject'        => $mostStudied && $mostStudied->total_minutes > 0 ? $mostStudied->name : null,
                'most_neglected_subject'      => $subjectCount >= 2 ? ($mostNeglected->name ?? null) : null,
                'highest_performing_subject'  => $highestPerforming ? $highestPerforming->name : null,
                'total_hours'                 => $totalHours,
            ]
        );
    }
}
