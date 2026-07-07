<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mark extends Model
{
    protected $fillable = ['subject_id', 'assessment_name', 'score', 'max_score', 'type', 'date'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'score' => 'integer',
            'max_score' => 'integer',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function percentage(): float
    {
        return $this->max_score > 0 ? round(($this->score / $this->max_score) * 100) : 0;
    }
}
