<?php

class Stmtzable{

    private static object $connection;
    private static PDOStatement $statement;

    public function __construct(object $connection){
        self::$connection = $connection;
    }

    public function query(string $sql){
        self::$statement = self::$connection->prepare($sql);
        var_dump(self::$statement);
        return $this;
    }

    public function bind(stdClass $params){
        $params = (array) $params;
        foreach($params as $key => $value){
            self::$statement->bindParam($key, $value);
        }
        return $this;
    }

    public function launch(){
        if(self::$statement->execute()){
            return self::$statement->fetchAll();
        }
        return [];
    }

}