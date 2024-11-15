<?php

use Interfaces\ModuleLoader;

class MainController extends ModuleLoader{

    protected static stdClass $query;
    protected static stdClass $parts;
    protected static stdClass $headers;
    protected static stdClass $paths;
    protected static Response $response;

    protected static bool $isFile = false;

    public function __construct(stdClass $config, string $module, string $basePath){
        parent::__construct($config, $module, $basePath);
        self::loadModules();
        self::setters();
        self::$response = new Response($config, $basePath);
    }

    private static function setters(){
        self::$paths = (object) self::objetizeAndBuildPaths(parent::$config->paths, parent::$basePath);
        self::objetizeAndGetParts();
        self::objetizeAndGetQuery();
    }

    public static function loadAndValidateFile(){              
        $wrapper = (array)self::$parts;
        unset($wrapper['']);
        $reservedPaths = array_keys((array) parent::$config->paths);
        if(!empty($wrapper) && in_array(current($wrapper),$reservedPaths)){
            self::$isFile = true;
            //ob_end_clean();
            $file = end($wrapper);
            $fileSplited = explode('.',$file);
            $extension = end($fileSplited);            
            $mime = Mime::getMimeByExtension($extension);
            $fileContent = @file_get_contents(self::$basePath.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $wrapper));
            if(!$fileContent){
                http_response_code(404);
                echo file_get_contents(self::$basePath.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'404.html');
            }else{
                header("Content-Type:$mime");
                echo $fileContent;
            }
        }                
    }

    public static function getIsFile(){
        return self::$isFile;
    }

    public function http(){    
        self::$headers = (object) self::objetizeHeaders(getallheaders()); 
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