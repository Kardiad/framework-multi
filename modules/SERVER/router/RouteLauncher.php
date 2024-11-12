<?php

class RouteLauncher{

    public static bool $found = false;

    public static function launchController(stdClass $config, stdClass $parts, stdClass $query){      
        $controllers = glob($config->controllers.DIRECTORY_SEPARATOR.'*.php');           
        foreach($controllers as $controller){
            require_once $controller;
            $arrayRoute = explode(DIRECTORY_SEPARATOR, $controller);           
            $className = array_pop($arrayRoute);            
            $class = str_replace('.php', '', $className);
            self::reflectionAndLaunch($class, $parts, $config);
        }
        if(!self::$found){            
            ob_end_clean();
            echo file_get_contents($config->templates."/404.html");
        }
    }

    private static function reflectionAndLaunch(string $class, stdClass $parts, stdClass $config){
        $controllerReflection = new ReflectionClass($class);
        $methods = $controllerReflection->getMethods();               
        foreach($methods as $method){
            $incomingPath = implode('/', (array)$parts);
            foreach($method->getAttributes(Route::class) as $path){
                $data = $path->newInstance();
                if(self::routeValidation($data, $incomingPath)){   
                    self::$found = true;                
                    $method->invoke($config);
                }
            }
        }
    }

    private static function routeValidation(Route $data, string $path){
        $routeWrapper = (object)$data->route;
        return 
            in_array($_SERVER['REQUEST_METHOD'], $routeWrapper->METHOD) 
            && $routeWrapper->PATH == $path;
    }

}