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

    private static function getDBPool(){
        $configDBPool = parent::$config->dbconnection;
        $wrapArrayToObjectPool = [];
        foreach($configDBPool as $config){                    
            $wrapArrayToObjectPool[$config->connectionName] = new Stmtzable($config);
        }
        self::$pool = (object)$wrapArrayToObjectPool;
    }

    public static function getInstancesOfDb(){
        return self::$pool;
    }

}