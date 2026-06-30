<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class PeerService
{
    public function resolveThresholds(User $user, string $normalizedName): array
    {
        $studentAvg = DB::selectOne("
            SELECT ROUND(AVG(m.score * 100.0 / m.max_score), 1) AS avg_percentage
            FROM marks m
            INNER JOIN subjects s ON s.id = m.subject_id AND s.normalized_name = ?
            WHERE s.user_id = ?
        ", [
            $normalizedName,
            $user->id,
        ]);

        $studentHasMark = $studentAvg && $studentAvg->avg_percentage !== null;

        if ($studentHasMark) {
            return [
                'threshold' => (float) $studentAvg->avg_percentage,
                'comparison' => '>',
                'mode' => 'relative',
            ];
        }

        return [
            'threshold' => 70,
            'comparison' => '>=',
            'mode' => 'absolute',
        ];
    }
}
