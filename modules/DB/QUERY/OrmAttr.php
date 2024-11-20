<?php

#[Attribute]
class OrmAttr{
    public function __construct(
        public string $name,
        public string $type,
        public bool $nullable = true,
        public bool $primary_key = false,
        public bool $auto_increment = false,
        public bool $unique = false,
        public bool $index = false,
        public object | null $joinClass = null
    ){}
}

#[Attribute]
class OrmTable{
    public function __construct(
        public string $table
    ){}
}