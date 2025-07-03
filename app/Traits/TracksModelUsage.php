<?php

namespace App\Traits;

use App\Debug\ModelTracker;

trait TracksModelUsage
{
    public static function bootTracksModelUsage(): void
    {
        static::retrieved(function ($model) {
            ModelTracker::add(get_class($model));
        });

        static::created(function ($model) {
            ModelTracker::add(get_class($model));
        });

        static::updated(function ($model) {
            ModelTracker::add(get_class($model));
        });

        static::deleted(function ($model) {
            ModelTracker::add(get_class($model));
        });
    }
}
