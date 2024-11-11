<?php

use Interfaces\ModuleLoader;

class Driver extends ModuleLoader{    

    private static string $driver = '';

    private static stdClass $config;

    public function __construct(stdClass $config, string $module, string $basePath){
        self::$config = $config;
        parent::loadModules($module, $basePath);        
    }

    public function getStmt(){
        //TODO generará una instancia de un Statement y de ahí iniciará las consultas
    }

    public function launchQuery() {
        //TODO lanzará la consulta SQL a través de parámetros de la clase query 
    }
}