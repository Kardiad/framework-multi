<?php

use Interfaces\ModuleLoader;

class Driver extends ModuleLoader{    

    private static string $driver = '';
    private static stdClass $pool;
    private static stdClass $result;

    public function __construct(stdClass $config, string $module, string $basePath){
        parent::__construct($config, $module, $basePath);
        parent::loadModules();  
        self::getDBPool();      
    }

    private static function driverFactory(string $driver, stdClass $dbConfig){
        return match($driver){
            'pdo-mysql' => new PDO("mysql:dbname=".$dbConfig->database.";host=".$dbConfig->host, $dbConfig->user, $dbConfig->password, $dbConfig->options),
            'pdo-postgre' => new PDO("pgsql:dbname=".$dbConfig->database.";host=".$dbConfig->host, $dbConfig->user, $dbConfig->password, $dbConfig->options)
        };
    }

    private static function getDBPool(){
        $configDBPool = parent::$config->dbconnection;
        $wrapArrayToObjectPool = [];
        foreach($configDBPool as $value){
            $wrapArrayToObjectPool[$value->connectionName] = new Stmtzable(self::driverFactory($value->driver, $value));
        }
        self::$pool = (object)$wrapArrayToObjectPool;
    }

    public static function getInstancesOfDb(){
        return self::$pool;
    }

}