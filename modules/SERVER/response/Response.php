<?php

class Response{

    private static object $config;
    private static string $basePath;

    public function __construct(stdClass $config, string $basePath){
        self::$config = $config;
        self::$basePath = $basePath;
    }

    public static function json(array|object $data){
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode($data, JSON_FORCE_OBJECT);
    }

    public static function template(string $template, array|object $data){  
        ob_end_clean();
        header('Content-Type: text/html');
        require_once self::$basePath.DIRECTORY_SEPARATOR.self::$config->paths->templates.DIRECTORY_SEPARATOR.$template;
    }

    public static function htmlTemplate(string $template){
        ob_end_clean();
        header('Content-Type:text/html');
        echo file_get_contents(self::$basePath.DIRECTORY_SEPARATOR.self::$config->paths->templates.DIRECTORY_SEPARATOR.$template);
    }

    public static function htmlPlain(string $content){
        ob_end_clean();
        header('Content-Type:text/html');
        echo $content;
    }

    public static function text(string $string){
        ob_end_clean();
        header('Content-Type:plain/text');
        echo $string;
    }

    public static function xml(){
        ob_end_clean();
        header('Content-Type: application/xml');
    }    

    public static function excel(){
       
    }

}