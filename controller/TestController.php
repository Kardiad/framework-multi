<?php

class TestController{   

    #[Route([
        "METHOD" => ["GET"],
        "PATH" => '/patata/wololo'
    ])]
    public static function index(){
        echo "<h1>hellowwww</h1>";
    }
    
}