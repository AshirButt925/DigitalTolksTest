<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = ['locale', 'key', 'content', 'tag'];

    // Optimized query scope
    public function scopeFilter($query, $filters)
    {
        return $query
            ->when($filters['locale'] ?? null, fn($q, $locale) => $q->where('locale', $locale))
            ->when($filters['key'] ?? null, fn($q, $key) => $q->where('key', 'LIKE', "%$key%"))
            ->when($filters['tag'] ?? null, fn($q, $tag) => $q->where('tag', $tag));
    }
}
