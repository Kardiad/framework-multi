<?php

use Interfaces\ModuleLoader;

class MainController extends ModuleLoader{

    protected static stdClass $query;

    protected static stdClass $parts;

    protected static stdClass $headers;

    protected static stdClass $paths;

    protected static stdClass $config;

    protected static string $basePath;

    protected static Response $response;

    public function __construct(stdClass $config, string $module, string $basePath){
        self::loadModules($module, $basePath);     
        self::$config = $config;
        self::$basePath = $basePath;
        self::$response = new Response($config, $basePath);
    }

    public function http(){
        self::$paths = (object) self::objetizeAndBuildPaths(self::$config->paths, self::$basePath);
        self::$headers = (object) self::objetizeHeaders(getallheaders()); 
        self::objetizeAndGetParts();
        self::objetizeAndGetQuery();
        RouteLauncher::launchController(self::$paths, self::$parts, self::$query);
    }

    private static function objetizeAndGetQuery(){
        self::$query = new stdClass;
        if($_SERVER && isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!=''){
            self::$query = (object) self::objetizePartsAndQuery(explode('&', $_SERVER['QUERY_STRING']));
        }
    }

    private static function objetizeAndGetParts(){
        self::$parts = new stdClass;
        if($_SERVER && isset($_SERVER['PHP_SELF'])){
            $baseUri = str_replace('/index.php/', '',$_SERVER['PHP_SELF']);
            self::$parts = (object) self::objetizePartsAndQuery(explode('/', $baseUri));
        }
    }

    private static function objetizeAndBuildPaths(stdClass $arrayPaths, string $basePath){
        $paths = [];
        foreach((array)$arrayPaths as $key => $value){
            $paths[$key] = $basePath.DIRECTORY_SEPARATOR.$value;
        }
        return $paths;
    }

    private static function objetizePartsAndQuery(array $arrayPartsQuery){
        $base = [];
        foreach($arrayPartsQuery as $part){            
            if(str_contains($part, '=')){
                $baseAttr = explode('=', $part);
                $base[$baseAttr[0]] = $baseAttr[1];
            }else{
                $base[$part] = $part;
            }
        }
        return $base;
    }

    private static function objetizeHeaders(array $headers){
        $base = [];
        foreach($headers as $key => $value){
            $validKey = strtolower(preg_replace('/\W+/', '', $key));
            $base[$validKey] = $value;
        }
        return $base;
    }

    protected static function toArray(object $object){
        return (array) $object;
    }

}