<?php

namespace App\Models\Concerns;

trait HasSingletonSettings
{
    protected static ?self $cached = null;

    public static function current(): self
    {
        if (static::$cached instanceof self) {
            return static::$cached;
        }

        static::$cached = static::query()->first() ?? new static(static::defaults());

        return static::$cached;
    }

    public static function clearCache(): void
    {
        static::$cached = null;
    }

    protected static function bootHasSingletonSettings(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }
}
