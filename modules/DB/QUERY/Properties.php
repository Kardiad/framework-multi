<?php

class Properties
{

    private ReflectionClass $properties;
    private array $propertiesArray;

    public function __construct(private string $className)
    {
        $this->start();
    }

    public function start()
    {
        $this->properties = new ReflectionClass($this->className);
        $this->propertiesArray = array_map(
            fn($propertie) => $propertie->getAttributes(OrmAttr::class)[0]->newInstance(),
            $this->properties->getProperties()
        );
        
    }

    public function getClass(){
        return (object)[
            'class' => $this->className,
            'properties' => $this->propertiesArray
        ];
    }
}
