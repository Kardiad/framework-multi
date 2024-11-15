<?php

use Interfaces\ModuleLoader;

class WebSocket extends ModuleLoader{

    protected static array $socketPool = [];
    protected static array $clients = [];
    protected static array $rooms = [];

    public function __construct(stdClass $config, string $module, string $basePath){
        parent::__construct($config, $module, $basePath);
        
    }

    private static function builder(){
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
            $socket = socket_create($parsedConfig->domain, $parsedConfig->type, $parsedConfig->protocol);
            
            if ($socket === false) {
                echo "Error creando el socket: " . socket_strerror(socket_last_error()) . "\n";
                continue;
            }

            socket_bind($socket, $config->host, $config->port);
            socket_listen($socket);

            self::$socketPool[$config->endpoint] = $socket;
        }
    }

    public static function stop(){
        foreach(self::$socketPool as $id => $socket){
            socket_close($socket);
            self::$socketPool[$id] = null;
        }
        echo time()." -> Sockets destruidos";
    }

    public static function run():never{  
        self::builder();   
        while(true){
            $readSockets = array_merge(array_values(self::$socketPool), array_values(self::$clients));
            $write = null;
            $except = null;
            socket_select($readSockets, $write, $except, 0, 10);

            // Acepta nuevos clientes en los sockets del pool
            foreach (self::$socketPool as $endpoint => $serverSocket) {
                if (in_array($serverSocket, $readSockets)) {
                    $newClient = socket_accept($serverSocket);
                    $clientId = uniqid();
                    self::$clients[$clientId] = $newClient;
                    echo "Nuevo cliente $clientId conectado en endpoint: $endpoint\n";
                }
            }

            // Lee datos de cada cliente y maneja mensajes
            foreach (self::$clients as $clientKey => $clientSocket) {
                if (in_array($clientSocket, $readSockets)) {
                    $data = socket_read($clientSocket, 1024);                    
                    if ($data === false || $data === "") {
                        unset(self::$clients[$clientKey]);
                        socket_close($clientSocket);
                        echo "Cliente desconectado\n";
                        continue;
                    }
                    $message = json_decode($data, true);
                    echo $message;
                    self::handleClientMessage($clientSocket, $message);
                }
            }
        }
    }

    private static function handleClientMessage($client, $message)
    {
        match($message['type'] ?? null) {
            'join'=> self::joinRoom($client, $message['room']),                
            'message' => self::sendMessageToRoom($message['room'], $message['data']),
            null => 'Tipo de mensaje desconocido'    
        };
    }

     private static function joinRoom($client, $room)
     {
         if (!isset(self::$rooms[$room])) {
             self::$rooms[$room] = [];
         }
         self::$rooms[$room][(int) $client] = $client;
         echo "Cliente unido a la sala $room\n";
     }
 
     private static function sendMessageToRoom($room, $message)
     {
         if (!isset(self::$rooms[$room])) {
             echo "Sala $room no existe\n";
             return;
         }
 
         foreach (self::$rooms[$room] as $client) {
             self::send($client, json_encode(["message" => $message]));
         }
     }
 
     // Envía datos a un cliente WebSocket
     private static function send($client, $data)
     {
         $frame = chr(129) . chr(strlen($data)) . $data;
         socket_write($client, $frame, strlen($frame));
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