<?php

class Stmtzable{

    private object $connection;
    private PDOStatement | null $statement;
    private stdClass $metadata;
    private stdClass $config;
    private string $basePath;

    public function __construct(stdClass $config, string $basePath){
        $this->config = $config;
        $this->basePath = $basePath;
        $this->factory();
    }

    private function factory(){
        switch($this->config->driver){
            case 'pdo-mysql':
                $this->connection = new PDO("mysql:dbname=".$this->config->database.";host=".$this->config->host, $this->config->user, $this->config->password, $this->config->options);
                break;
            case 'pdo-postgre':
                break;
        }
    }

    public function query(string $sql){
        $this->statement = $this->connection->prepare($sql);
        return $this;
    }

    public function getRepository(string $className){
        return new Query($className, $this->config, $this->basePath);
    }

    private function getMetadata(){
        $metadata = [];
        $metadata['columnCount'] = $this->statement->columnCount();
        for($x=0; $x<$metadata['columnCount']; ++$x){
            $metadata['columnMetadata'][] = $this->statement->getColumnMeta($x);
        }
        $this->metadata = (object) $metadata;
    }

    public function bind(stdClass $params){
        $params = (array) $params;
        foreach($params as $key => $value){
            if(!$this->statement->bindValue(':'.$key, $value)){
                throw new Error("the param $key with $value can not be bound", 500);
            }
        }
        return $this;
    }

    public function getDBName(){
        return $this->config->connectionName;
    }

    public function launch(){        
        if($this->statement->execute()){
            $this->getMetadata();
            $result['data'] = $this->statement->fetchAll();
            $result['metadata'] = $this->metadata;
            $this->statement = null;
            return $result;
        }
        $this->getMetadata();
        $result['metadata'] = $this->metadata;
        $result['data'] = [];
        $this->statement = null;
        return [];
    }

}