<?php
namespace Interfaces;
abstract class Parts {
    protected array $parts = [];

    public function pushParts($data){
        $this->parts[] = $data;
    }

    public function getParts(){
        return $this->parts;
    }

    public abstract function getPart(string $key);
    // This one will be use to get every starter of query, for example
    // SELECT (FIELDS)
    // INSERT INTO (FIELDS)
    // UPADTE (FIELDS)
    // DELETE (FROM)
    public abstract function addHeader(); 
    // This one will be use to get every bind value, for example
    // SELECT (VALUES) (joins)
    // INSERT INTO (VALUES) VALUES (VAL1, VAL2, VAL3)
    // UPDATE FIELD1 FIELD2 FIELD3
    // DELETE FROM [TABLE] WHERE [VAL1]
    public abstract function addValues();
    //This one will have to set the values or save values in classes
    //WHERE [VAL1:COND:VAL1VALUE]
    //INSERT INTO (VALUES) VALUES (VAL1:REPLACED:VAL1)
    //UPDATE FIELD1 = FIELD1:REPLACED:VAL1 WHERE FIELD:REPLACED:VALWHERE
    //DELETE FROM [TABLE] WHERE [VAL1:REPLACE:VAL1]
    public abstract function addBinders();
}