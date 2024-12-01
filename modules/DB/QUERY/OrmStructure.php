<?php

use Interfaces\ModelsLoader;

class OrmStructure extends ModelsLoader{
    
    private object $entity;

    private array $recursiv;

    public function __construct(private string $className, private stdClass $config, private string $basePath, PDO $connection){
        self::loadModels($basePath, $config);
        $this->matchObjectWithTable();
        $this->recursiveClassEntity();
    }
    private function matchObjectWithTable(){
        $this->entity = $this->getReflectionClass($this->className);
        $this->recursiveClassEntity();
    }

    private function recursiveClassEntity(){
        foreach($this->entity->properties as $key => $dbField){
           $subentities =  $this->extractSubentity($dbField);            
           $this->entity->properties[$key] = $subentities;
        }
    }

    private function extractSubentity(object $field){
        if(@$field->joinClass != '' && !is_object(@$field->joinClass)){            
            $candidateRecursive = $this->getReflectionClass($field->joinClass);
            foreach($candidateRecursive->properties as $property){
                if($property->joinClass != '' && !is_object($property->joinClass)){
                    $property->joinClass = $this->extractSubentity($property);                   
                }
            }
            return $candidateRecursive;          
        }
        return $field;
    }

    private function getReflectionClass(string $class){
        //while here to find every class that use every subclasses
        return (new Properties($class))->getClass();
    }

}