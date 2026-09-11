<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Database\Factories\InstrumentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instrument extends Model
{
    /** @use HasFactory<InstrumentFactory> */
    use HasFactory, HasUniqueSlug;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'file',
        'file_type',
        'file_size',
        'year',
        'download_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'year' => 'integer',
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
            ? $query->whereRaw('LOWER(title) LIKE ?', ['%'.mb_strtolower($term).'%'])
            : $query;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
