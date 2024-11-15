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
        $data = Driver::getInstancesOfDb()
            ->default
            ->query('SELECT * FROM usuarios')
            ->bind(new stdClass)
            ->launch();
        self::$response::template('test.php', (object)$data);
    }
}