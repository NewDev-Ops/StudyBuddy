<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class ConnectRequest extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'subject_name', 'note', 'status', 'responded_at'];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    public function scopeBetween(Builder $query, User $a, User $b): Builder
    {
        return $query->where(function ($q) use ($a, $b) {
            $q->where('sender_id', $a->id)->where('receiver_id', $b->id);
        })->orWhere(function ($q) use ($a, $b) {
            $q->where('sender_id', $b->id)->where('receiver_id', $a->id);
        });
    }
}
