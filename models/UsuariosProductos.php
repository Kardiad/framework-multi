<?php
#[OrmTable('usuarios_productos')]
class UsuariosProductos{

    #[OrmAttr(
        'usuario_id', 
        'relationship', 
        false, 
        false, 
        false, 
        false, 
        true, 
        Usuarios::class)]
    private Usuarios $usuario;

    #[OrmAttr(
        'producto_id', 
        'relationship', 
        false, 
        false, 
        false, 
        false, 
        true, 
        Productos::class)]
    private Productos $productos;

    #[OrmAttr('ud', 'string')]
    private string $ud;

    #[OrmAttr('ud_total', 'int')]
    private int $ud_total;

}