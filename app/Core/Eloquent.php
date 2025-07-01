<?php
namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;
use  App\Core\DatabaseManager;

class Eloquent
{
    public static function boot(): void
    {
        $capsule = new Capsule();
        $config = DatabaseManager::getConfig();
        $capsule->addConnection($config['connections'][$config['default']]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
