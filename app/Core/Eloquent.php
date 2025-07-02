<?php
namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Eloquent
{
    public static function boot(): void
    {
        $capsule = new Capsule();
        $config = DatabaseManager::getConfig();

        $capsule->addConnection($config['connections'][$config['default']]);
        $capsule->setEventDispatcher(new Dispatcher(new Container()));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        // 🐞 Log SQL en mode dev
        if (is_dev()) {
            $capsule->getConnection()->enableQueryLog();

            $capsule->getConnection()->listen(function ($query) {
                $sql = $query->sql;
                $bindings = implode(', ', array_map(function ($binding) {
                    if (is_string($binding)) {
                        return "'$binding'";
                    } elseif ($binding === null) {
                        return 'NULL';
                    }
                    return $binding;
                }, $query->bindings));
                $time = $query->time;

                $log = "[" . date('Y-m-d H:i:s') . "] $sql [bindings: $bindings] (⏱ {$time}ms)\n";
                file_put_contents(ROOT . '/storage/logs/sql.log', $log, FILE_APPEND);
            });
        }
    }
}
