<?php

use App\Core\Lang;

if (!function_exists('lang')) {
    function lang(string $key): string
    {
        return Lang::get($key);
    }
}
function is_dev(): bool {
    return ($_ENV['APP_ENV'] ?? 'prod') === 'dev';
}
