<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{   
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'university_id',
        'is_opted_in',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_opted_in' => 'boolean',
        ];
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function hasCompletedOnboarding(): bool
    {
        return $this->subjects()->exists() || $this->is_opted_in;
    }

    public ?array $suggestionBreakdown = null;

    public function suggestedSubject(): ?Subject
    {
        $subjects = $this->subjects()
            ->leftJoin('revision_sessions', 'subjects.id', '=', 'revision_sessions.subject_id')
            ->selectRaw('
                subjects.*,
                COALESCE(SUM(revision_sessions.duration_minutes), 0) as total_minutes,
                MAX(revision_sessions.date) as last_studied_date
            ')
            ->groupBy(
                'subjects.id', 'subjects.user_id', 'subjects.name',
                'subjects.normalized_name', 'subjects.color_code',
                'subjects.created_at', 'subjects.updated_at'
            )
            ->orderBy('subjects.name')
            ->get();

        if ($subjects->isEmpty()) {
            $this->suggestionBreakdown = null;
            return null;
        }

        $subjectIds = $subjects->pluck('id');
        $marks = Mark::whereIn('subject_id', $subjectIds)
            ->selectRaw('subject_id, type, AVG(score * 1.0 / max_score) as avg_ratio')
            ->groupBy('subject_id', 'type')
            ->get()
            ->groupBy('subject_id');

        $typeWeights = ['Exam' => 0.50, 'Test' => 0.30];
        $otherWeight = 0.20;
        $maxMinutes = (int) $subjects->max('total_minutes');

        $scored = [];

        foreach ($subjects as $subject) {
            $totalMinutes = (int) $subject->total_minutes;

            if ($maxMinutes === 0) {
                $neglectScore = 1.0;
            } else {
                $neglectScore = 1.0 - ($totalMinutes / $maxMinutes);
            }

            $subjectMarks = $marks->get($subject->id, collect());

            $weightedSum = 0.0;
            $weightTotal = 0.0;

            foreach ($subjectMarks as $m) {
                $typeWeight = $typeWeights[$m->type] ?? $otherWeight;
                $weightedSum += (float) $m->avg_ratio * $typeWeight;
                $weightTotal += $typeWeight;
            }

            if ($weightTotal == 0.0) {
                $underperformanceScore = null;
                $performanceWeight = 0.0;
                $studyWeight = 1.0;
                $weightedAvgPercent = null;
            } else {
                $weightedAvg = $weightedSum / $weightTotal;
                $underperformanceScore = 1.0 - $weightedAvg;
                $performanceWeight = 0.5;
                $studyWeight = 0.5;
                $weightedAvgPercent = round($weightedAvg * 100, 1);
            }

            $priority = ($neglectScore * $studyWeight)
                      + (($underperformanceScore ?? 0.0) * $performanceWeight);

            $scored[] = [
                'subject'                => $subject,
                'neglect_score'          => round($neglectScore, 4),
                'underperformance_score' => $underperformanceScore !== null ? round($underperformanceScore, 4) : null,
                'priority_score'         => round($priority, 4),
                'total_minutes'          => $totalMinutes,
                'weighted_avg_percent'   => $weightedAvgPercent,
                'mode'                   => $weightTotal == 0.0 ? 'neglect_only' : 'composite',
            ];
        }

        usort($scored, fn($a, $b) => $b['priority_score'] <=> $a['priority_score']
            ?: $a['subject']->name <=> $b['subject']->name);

        $best = $scored[0];

        $this->suggestionBreakdown = [
            'subject_name'           => $best['subject']->name,
            'neglect_score'          => $best['neglect_score'],
            'underperformance_score' => $best['underperformance_score'],
            'priority_score'         => $best['priority_score'],
            'mode'                   => $best['mode'],
            'total_minutes'          => $best['total_minutes'],
            'weighted_avg_percent'   => $best['weighted_avg_percent'],
        ];

        return $best['subject'];
    }
}
