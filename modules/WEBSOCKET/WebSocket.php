<?php

use Interfaces\ModuleLoader;

class WebSocket extends ModuleLoader{

    protected static array $socketPool = [];

    public function __construct(stdClass $config, string $module, string $basePath){
        parent::__construct($config, $module, $basePath);
        if(function_exists('socket_create')){
            self::createSockets();
        }else{
            self::installSockets();
            self::createSockets();
        }
    }

    private static function installSockets(){
        //TODO crear comandos para instalación de sockets
    }

    private static function createSockets(){
        foreach(parent::$config->websocket as $config){
            $parsedConfig = self::parseConfing($config);
            self::$socketPool[] = socket_create($parsedConfig->domain, $parsedConfig->type, $parsedConfig->protocol);
        }
    }

    public static function run(){
        //var_dump(self::$socketPool);
    }

    private static function parseConfing(stdClass $config){
        return (object)[
            'domain' => match($config->domain){
                'inet' => 2,
                'inet6' => 10,
                'unix' => 1                
            },
            'type' => match($config->type){
                'full-duplex' => 1,
                'datagram' => 2,
                'packet' => 5,
                'row' => 3,
                'rdm' => 4
            },
            'protocol' => match($config->protocol){
                'tcp' => 6,
                'udp' => 17                
            }
        ];
    }

}