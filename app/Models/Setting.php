<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    private static ?Collection $cache = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        self::$cache ??= static::query()->pluck('value', 'key');

        return self::$cache[$key] ?? $default;
    }

    public static function flushCache(): void
    {
        self::$cache = null;
    }

    protected static function booted(): void
    {
        static::saved(fn () => self::$cache = null);
        static::deleted(fn () => self::$cache = null);
    }
}
