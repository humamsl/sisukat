<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Database\Factories\TutorialFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tutorial extends Model
{
    /** @use HasFactory<TutorialFactory> */
    use HasFactory, HasUniqueSlug;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'content',
        'thumbnail',
        'video_url',
        'file',
        'type',
        'external_url',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $term
            ? $query->whereRaw('LOWER(title) LIKE ?', ['%'.mb_strtolower($term).'%'])
            : $query;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Convert a YouTube/Vimeo watch URL into its embeddable iframe URL.
     * Returns null if the URL doesn't match a known provider.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([\w-]{11})/', $this->video_url, $m)) {
            return "https://www.youtube.com/embed/{$m[1]}";
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $m)) {
            return "https://player.vimeo.com/video/{$m[1]}";
        }

        return null;
    }
}
