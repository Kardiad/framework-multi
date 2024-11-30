<?php

class Properties
{

    private ReflectionClass $properties;
    private array $propertiesArray;
    private string $tableName;

    public function __construct(private string $className)
    {
        $this->start();
    }

    public function start()
    {
        $this->properties = new ReflectionClass($this->className);
        $this->tableName = $this->properties->getAttributes(OrmTable::class)[0]->newInstance()->table;
        $this->propertiesArray = array_map(
            fn($propertie) => $propertie->getAttributes(OrmAttr::class)[0]->newInstance(),
            $this->properties->getProperties()
        );
        
    }

    public function getClass(){
        return (object)[
            'tableName' => $this->tableName,
            'class' => $this->className,
            'properties' => $this->propertiesArray
        ];
    }
}
