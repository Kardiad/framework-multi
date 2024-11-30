<?php

use Interfaces\ModelsLoader;

class Query extends ModelsLoader{
    
    private object $entity;

    private array $structureByEntity;


    public function __construct(private string $className, private stdClass $config, private string $basePath, PDO $connection){               
        require_once $basePath.DIRECTORY_SEPARATOR.$config->ormFolderEntity.DIRECTORY_SEPARATOR.$this->className.'.php';
        self::loadModels($basePath, $config);
        $this->matchObjectWithTable();
        $this->recursiveClassEntity();
    }
    private function matchObjectWithTable(){
        $this->entity = $this->getReflectionClass($this->className);
        $this->recursiveClassEntity();
    }

    private function recursiveClassEntity(){
        foreach($this->entity->properties as $dbField){
            $this->extractSubentity($dbField);            
        }
        $this->entity->properties = array_filter($this->entity->properties, fn($e)=>$e->joinClass == '');
        array_unshift($this->structureByEntity, $this->entity);
        echo '<pre>'; print_r($this->structureByEntity); echo '</pre>'; 
        exit;
    }

    private function extractSubentity(object $field){
        if($field->joinClass != ''){
            $candidateRecursive = $this->getReflectionClass($field->joinClass);            
            foreach($candidateRecursive->properties as $properties){                
                $this->extractSubentity($properties);
            }
            $this->structureByEntity[] = $candidateRecursive;            
        }
    }

    private function getReflectionClass(string $class){
        //while here to find every class that use every subclasses
        return (new Properties($class))->getClass();
    }

}