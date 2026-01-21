<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Database {
    private static $instance = null;

    private function __construct() {
        // private 생성자로 외부 인스턴스 생성을 막음
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Capsule();
            self::$instance->addConnection([
                'driver'    => 'mysql',
                'host'      => $_ENV['DB_HOST'],
                'database'  => $_ENV['DB_DATABASE'],
                'username'  => $_ENV['DB_USERNAME'],
                'password'  => $_ENV['DB_PASSWORD'],
                'charset'   => $_ENV['DB_CHARSET'],
                'collation' => $_ENV['DB_COLLATION'],
                'prefix'    => '',
            ]);

            self::$instance->setEventDispatcher(new Dispatcher(new Container));
            self::$instance->setAsGlobal();
            self::$instance->bootEloquent();
        }

        return self::$instance;
    }
}