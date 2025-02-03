<?php

use Interfaces\ModelsLoader;

class OrmStructure extends ModelsLoader{
    
    private object $entity;

    private array $parts;

    private object $possibleQueries;

    private Select $select;

    public function __construct(private string $className, private stdClass $config, private string $basePath, PDO $connection){
        $this->select = new Select();
        self::loadModels($basePath, $config);
        $this->matchObjectWithTable();
        $this->recursiveClassEntity();
    }
    private function matchObjectWithTable(){
        $this->entity = $this->getReflectionClass($this->className);
        $this->recursiveClassEntity();
        $this->select->addHeader();
        echo '<pre>'; print_r($this->select->getPart('parts')); echo '</pre>';
        exit;
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
                $this->pushParts([
                    'maintable' => $this->entity->tableName,
                    'select' =>  $candidateRecursive->tableName.'.'.$property->name,
                    'candidate' => $candidateRecursive,
                    'property' => $property
                ]);
                if($property->joinClass != '' && !is_object($property->joinClass)){                                       
                    $property->joinClass = $this->extractSubentity($property);                   
                }
            }            
            return $candidateRecursive;
        }
        return $field;
    }

    private function pushParts(array $data){
        $this->select->pushParts($data);
        //add update or other kind of queries
    }

    private function getReflectionClass(string $class){
        //while here to find every class that use every subclasses
        return (new Properties($class))->getClass();
    }

    public function getPossibleQueries() {
        return $this->possibleQueries;
    }

}