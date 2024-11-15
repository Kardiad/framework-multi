<?php

class SecondController extends MainController{

    #[Route([
        "METHOD" => ["GET"],
        "PATH" => '/patata/pocholo'
    ])]
    public static function index(){        
        Response::json(['name' => 'POCHOLO']);
    }

    #[Route([
        "METHOD" => ["GET"],
        "PATH" => '/patata/curro'
    ])]
    public static function htmlTemplate(){     
        $params = new stdClass;
        $params->id = 5;
        $params->nombre = '%at%';
        $data = Driver::getInstancesOfDb()
            ->default
            ->query('SELECT * FROM usuarios WHERE id = :id and nombre like :nombre')
            ->bind($params)
            ->launch();
        self::$response::template('test.php', (object)$data);
    }
}