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

    public function suggestedSubject(): ?Subject
    {
        return $this->subjects()
            ->leftJoin('revision_sessions', 'subjects.id', '=', 'revision_sessions.subject_id')
            ->selectRaw('subjects.*, MAX(revision_sessions.date) as last_studied_date')
            ->groupBy('subjects.id', 'subjects.user_id', 'subjects.name', 'subjects.color_code', 'subjects.created_at', 'subjects.updated_at')
            ->orderByRaw('last_studied_date IS NULL DESC, last_studied_date ASC, subjects.name ASC')
            ->first();
    }
}
