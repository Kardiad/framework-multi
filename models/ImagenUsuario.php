<?php


#[OrmTable('imagen_usuario')]
class ImagenUsuario{

    #[OrmAttr(
        'id', 
        'integer', 
        false, 
        true, 
        true, 
        true)]
    private $id;

    #[OrmAttr('nombre', 'varchar')]
    private string $fichero;

    #[OrmAttr('nombre', 'varchar')]
    private float $formato;

    #[OrmAttr('nombre', 'varchar')]
    private string $ruta;

}