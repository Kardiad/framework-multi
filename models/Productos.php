<?php 

#[OrmTable('productos')]
class Productos{

    #[OrmAttr(
        'id', 
        'varchar', 
        false, 
        true, 
        true, 
        true)]
    private $id;

    #[OrmAttr('nombre', 'varchar')]
    private string $nombre;

    #[OrmAttr('nombre', 'double')]
    private float $precio_base;

}