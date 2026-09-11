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
}
