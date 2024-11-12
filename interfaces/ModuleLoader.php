<?php

namespace Interfaces;

use stdClass;

abstract class ModuleLoader{ 

    protected static stdClass $config; 
    protected static string $module;
    protected static string $basePath;

    public function __construct(stdClass $config, string $module, string $basePath){
        self::$config = $config;
        self::$module = $module;
        self::$basePath = $basePath;
    }

    protected static function loadModules(){
        // Load modules
        $modulePath = self::$basePath."/modules/".self::$module.'/';
        foreach(scandir($modulePath) as $moduleFile){ 
            if(!str_contains('./', $moduleFile)){
                foreach(glob("$modulePath$moduleFile/*.php") as $class){                    
                    require_once $class;
                }
            }
        }
    }    
}