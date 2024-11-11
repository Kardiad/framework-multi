<?php

class SecondController{
    #[Route([
        "METHOD" => ["GET"],
        "PATH" => 'patata/pocholo'
    ])]
    public static function index(){        
        echo json_encode([
            'name' => 'POCHOLO'
        ]);
    }
}