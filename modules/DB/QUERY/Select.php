<?php

use Interfaces\Parts;

class Select extends Parts{

    private string $select = '';
    private string $from;
    private string $join;
    private string $where;

    public function addHeader(){
        //here we add every select n1, n2, n3 ... 
        $x = count($this->parts)/2-1;
        $y = count($this->parts)/2-1;
        $endX = count($this->parts);
        $endY = 0;
        for($x, $y; $x<$endX, $y>=$endY; ++$x, --$y){
                if($x != $y){
                    $this->select .= $this->parts[$y]['select'] . ', ';
                }
                $this->select .= $this->parts[$x]['select'] . ', ';
        }        
        $this->select = 'SELECT '.substr($this->select, 0, strlen($this->select)-2);
    }

    public function addValues() {
        //here we add every join and from 
    }

    public function addBinders(){
        //here we add every where.
    }

    //Implement some things to implement a logic which make select from join where

    public function getPart(string $key){
        return $this->$key;
    }

}