<?php

namespace App\Services;

class SubjectNormalizer
{
    const SYNONYM_MAP = [
        'maths' => 'mathematics',
        'math' => 'mathematics',
        'bio' => 'biology',
        'chem' => 'chemistry',
        'phys' => 'physics',
        'cs' => 'computer science',
        'comp sci' => 'computer science',
        'stat' => 'statistics',
        'stats' => 'statistics',
        'eng' => 'english',
        'sci' => 'science',
    ];

    const FILLER_PREFIXES = [
        'intro to ',
        'introduction to ',
        'fundamentals of ',
        'basics of ',
        'principles of ',
        'foundations of ',
        'essentials of ',
    ];

    public static function normalize(string $name): string
    {
        $value = trim(strtolower($name));

        foreach (static::FILLER_PREFIXES as $prefix) {
            if (str_starts_with($value, $prefix)) {
                $value = trim(substr($value, strlen($prefix)));
                break;
            }
        }

        $multiWordSynonyms = array_filter(static::SYNONYM_MAP, fn ($key) => str_contains($key, ' '), ARRAY_FILTER_USE_KEY);
        $singleWordSynonyms = array_diff_key(static::SYNONYM_MAP, $multiWordSynonyms);

        uksort($multiWordSynonyms, fn ($a, $b) => strlen($b) <=> strlen($a));

        foreach ($multiWordSynonyms as $key => $replacement) {
            if (str_contains($value, $key)) {
                $value = str_replace($key, $replacement, $value);
            }
        }

        $words = preg_split('/\s+/', $value);
        $mapped = array_map(fn ($w) => $singleWordSynonyms[$w] ?? $w, $words);

        return trim(preg_replace('/\s+/', ' ', implode(' ', $mapped)));
    }
}
