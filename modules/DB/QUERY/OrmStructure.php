<?php

use Interfaces\ModelsLoader;

class OrmStructure extends ModelsLoader{
    
    private object $entity;

    private array $parts;

    private object $possibleQueries;

    public function __construct(private string $className, private stdClass $config, private string $basePath, PDO $connection){
        $this->possibleQueries = new stdClass;
        $this->possibleQueries->select = '';
        $this->possibleQueries->insert = '';
        $this->possibleQueries->update = '';
        $this->possibleQueries->delete = '';
        $this->possibleQueries->create = '';
        self::loadModels($basePath, $config);
        $this->setParts();
        $this->matchObjectWithTable();
        $this->recursiveClassEntity();
    }
    private function matchObjectWithTable(){
        $this->entity = $this->getReflectionClass($this->className);
        $this->parts['from'] = ' FROM '.$this->entity->tableName.' ';
        $this->recursiveClassEntity();
    }

    private function setParts(){
        $this->parts['select'] = '';
        $this->parts['where'] = '';
        $this->parts['join'] = '';
        $this->parts['order'] = '';
        $this->parts['limit'] = '';
        $this->parts['group'] = '';
        $this->parts['from'] = '';
        $this->parts['values'] = '';
    }

    private function recursiveClassEntity(){
        foreach($this->entity->properties as $key => $dbField){
           $subentities = $this->extractSubentity($dbField);            
           $this->entity->properties[$key]->joinClass = $subentities;
        }         
    }

    private function extractSubentity(object $field){
        if(@$field->joinClass != '' && !is_object(@$field->joinClass)){ 
            $candidateRecursive = $this->getReflectionClass($field->joinClass);
            //Add join on field and candidate where index            
            foreach($candidateRecursive->properties as $property){
                $this->parts['select'].= $candidateRecursive->tableName.'.'.$property->name.','; 
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

    public function getPossibleQueries() {
        return $this->possibleQueries;
    }

}