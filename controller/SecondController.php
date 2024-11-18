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
        $params->id = 1;       
        $data = Driver::getInstancesOfDb()
            ->default
            ->query('SELECT 
                u.nombre as usuario_nombre, 
                p.nombre as producto_nombre,
                up.usuario_id as join_usuario,
                up.producto_id as join_producto
                FROM usuarios u
                JOIN usuarios_productos up ON u.id = up.usuario_id
                JOIN productos p ON p.id = up.producto_id 
                where u.id = :id;')
            ->bind($params)
            ->launch();
        $data['ormValues'] = Driver::getInstancesOfDb()->default->getRepository(Usuarios::class);
        die();
        //var_dump($data['ormValues']); die();
        self::$response::template('test.php', (object)$data);
    }
}