<?php

#[OrmTable('usuarios')]
class Usuarios
{

    #[OrmAttr(
        'id', 
        'varchar', 
        false, 
        true, 
        true, 
        true)]
    private int $id;
    #[OrmAttr('nombre', 'varchar')]
    private string $nombre;
    #[OrmAttr('username', 'varchar')]
    private string $username;
    #[OrmAttr('pass', 'varchar')]
    private string $pass;

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
        return $this;
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername($username){
        $this->username = $username;
        return $this;
    }

    public function getPass(){
        return $this->pass;
    }

    public function setPass($pass){
        $this->pass = $pass;
        return $this;
    }
}
