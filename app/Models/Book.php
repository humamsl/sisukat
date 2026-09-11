<?php

namespace App\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'author',
        'year',
        'pages_count',
        'cover',
        'file',
        'download_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'pages_count' => 'integer',
            'download_count' => 'integer',
        ];
    }

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
            ? $query->where(fn (Builder $q) => $q
                ->whereRaw('LOWER(title) LIKE ?', ['%'.mb_strtolower($term).'%'])
                ->orWhereRaw('LOWER(author) LIKE ?', ['%'.mb_strtolower($term).'%']))
            : $query;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
