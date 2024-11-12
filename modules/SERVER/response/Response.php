<?php

class Response{

    private static object $config;
    private static string $basePath;

    public function __construct(stdClass $config, string $basePath){
        self::$config = $config;
        self::$basePath = $basePath;
    }

    public static function json(array|object $data){
        header('Content-Type: application/json');
        echo json_encode($data, JSON_FORCE_OBJECT);
    }

    public static function template(string $template, array|object $data){  
        header('Content-Type: text/html');
        require_once self::$basePath.DIRECTORY_SEPARATOR.self::$config->paths->templates.DIRECTORY_SEPARATOR.$template;
    }

    public static function html(){
        header('Content-Type:text/html');
    }

    public static function text(){
        header('Content-Type:plain/text');
    }

    public static function xml(){
        header('Content-Type: application/xml');
    }    

    public static function excel(){
       
    }

}