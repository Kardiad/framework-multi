<?php

class Query{
    
    private object $entity;

    private object $dbProperties;

    public function __construct(private string $className, private stdClass $config, private string $basePath, PDO $connection){               
        require_once $basePath.DIRECTORY_SEPARATOR.$config->ormFolderEntity.DIRECTORY_SEPARATOR.$this->className.'.php';
        $this->matchObjectWithTable($connection);
    }
    private function matchObjectWithTable(PDO $connection){ 
        $this->entity = (new Properties($this->className))->getClass();
        $stmt = $connection->prepare('SHOW COLUMNS FROM '.$this->className);
        $metadataResultSet = [];
        if($stmt->execute()){
            $metadataResultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo '<pre>'; print_r([$metadataResultSet]); echo '</pre>';      
    }

}