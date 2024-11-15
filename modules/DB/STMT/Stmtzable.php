<?php

class Stmtzable{

    private static object $connection;
    private static PDOStatement | null $statement;

    public function __construct(object $connection){
        self::$connection = $connection;
    }

    public function query(string $sql){
        self::$statement = self::$connection->prepare($sql);
        return $this;
    }

    public function bind(stdClass $params){
        $params = (array) $params;
        foreach($params as $key => $value){
            if(!self::$statement->bindValue(':'.$key, $value)){
                throw new Error("the param $key with $value can not be bound", 500);
            }
        }
        return $this;
    }

    public function launch(){        
        if(self::$statement->execute()){
            $result = self::$statement->fetchAll();
            self::$statement = null;
            return $result;
        }
        self::$statement = null;
        return [];
    }

}