<?php

class Query{
    
    private Properties $entity;


    public function __construct(private string $className, private stdClass $config, private string $basePath){               
        require_once $basePath.DIRECTORY_SEPARATOR.$config->ormFolderEntity.DIRECTORY_SEPARATOR.$this->className.'.php';
        $this->getReflectionClass();
    }
    private function getReflectionClass(){ 
        $this->entity = new Properties($this->className);        
    }

}