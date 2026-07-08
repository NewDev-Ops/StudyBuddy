<?php

namespace App\Models;

use App\Services\SubjectNormalizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'name', 'color_code'];

    protected function casts(): array
    {
        return [
            'normalized_name' => 'string',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Subject $subject) {
            $subject->normalized_name = SubjectNormalizer::normalize($subject->name);
        });

        static::updating(function (Subject $subject) {
            if ($subject->isDirty('name')) {
                $subject->normalized_name = SubjectNormalizer::normalize($subject->name);
            }
        });
    }

    public static function normalizeName(string $name): string
    {
        return SubjectNormalizer::normalize($name);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function revisionSessions(): HasMany
    {
        return $this->hasMany(RevisionSession::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
}
