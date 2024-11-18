<?php

class Properties{

    private ReflectionClass $properties;
    private array $propertiesArray;

    public function __construct(private string $className){
        $this->start();
    }

    public function start(){
        $this->properties = new ReflectionClass($this->className);
        $this->propertiesArray = array_map(function($propertie){
            return $propertie->getAttributes(OrmAttr::class)[0];
        }, $this->properties->getProperties());
        echo '<pre>'; print_r( $this->propertiesArray); echo '</pre>';
    }

}