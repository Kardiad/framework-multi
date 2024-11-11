<?php

namespace Interfaces;
abstract class ModuleLoader{
    protected static function loadModules(string $module, string $basePath){
        // Load modules
        $modulePath = $basePath."/modules/".$module.'/';
        foreach(scandir($modulePath) as $moduleFile){ 
            if(!str_contains('./', $moduleFile)){
                foreach(glob("$modulePath$moduleFile/*.php") as $class){
                    ////print_r("$class <br>\n");
                    require_once $class;
                }
            }
        }
    }    
}