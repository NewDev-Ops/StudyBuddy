<?php

namespace App\Models;

use App\Services\SubjectNormalizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    protected $fillable = ['university_id', 'title', 'url', 'subject_tag', 'normalized_subject_tag'];

    protected function casts(): array
    {
        return [
            'normalized_subject_tag' => 'string',
        ];
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Resource $resource) {
            $resource->normalized_subject_tag = SubjectNormalizer::normalize($resource->subject_tag);
        });

        static::updating(function (Resource $resource) {
            if ($resource->isDirty('subject_tag')) {
                $resource->normalized_subject_tag = SubjectNormalizer::normalize($resource->subject_tag);
            }
        });
    }
}
