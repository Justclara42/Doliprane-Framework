<?php

namespace App\Debug;

class ModelTracker
{
    protected static array $models = [];

    public static function add(string $modelClass): void
    {
        self::$models[] = $modelClass;
        self::$models = array_unique(self::$models); // éviter les doublons
    }

    public static function getTracked(): array
    {
        return self::$models;
    }

    public static function reset(): void
    {
        self::$models = [];
    }
}
