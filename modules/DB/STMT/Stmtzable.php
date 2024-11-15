<?php

class Stmtzable{

    private static object $connection;
    private static PDOStatement | null $statement;
    private static stdClass $metadata;

    public function __construct(object $connection){
        self::$connection = $connection;
    }

    public function query(string $sql){
        self::$statement = self::$connection->prepare($sql);
        return $this;
    }

    private static function getMetadata(){
        //TODO refactor to do a metadata class
        $metadata = [];
        $metadata['columnCount'] = self::$statement->columnCount();
        for($x=0; $x<$metadata['columnCount']; ++$x){
            $metadata['columnMetadata'][] = self::$statement->getColumnMeta($x);
        }
        self::$metadata = (object) $metadata;
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
            self::getMetadata();
            $result['data'] = self::$statement->fetchAll();
            $result['metadata'] = self::$metadata;
            self::$statement = null;
            return $result;
        }
        self::getMetadata();
        $result['metadata'] = self::$metadata;
        $result['data'] = [];
        self::$statement = null;
        return [];
    }

}