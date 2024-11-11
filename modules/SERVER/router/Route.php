<?php

#[Attribute]
class Route{
    public function __construct(public array $route){}
}