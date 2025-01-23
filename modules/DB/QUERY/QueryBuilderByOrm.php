<?php 

class QueryBuilderByOrm{

    public function __construct(private object $ormStructure, private Stmtzable $wrap){}

    public function getAll(){
        $select = $this->ormStructure->getPossibleQueries()->select;
        if($select != ''){
            return $this->wrap->query($select)->launch();
        }
        throw new ErrorException("Framework error, there is not a valid query, your query is blank", 500);
    }

    public function getOneBy(){}

    public function find(){}

    public function persist(){}

    public function refresh(){}

    public function remove(){}

    private function getQuery(){
        
    }
    
}