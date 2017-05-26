<?php

class Rol
{
    private $array = array("Anonimo","Usuario","Colaborador","Editor","Administrador");
    //                      0         1          2             3        4
    private $usuarios;
    public function __construct()
    {
        if(file_exists('config/config.json'))
        {
            $_CONF = json_decode(file_get_contents('config/config.json'));
        }else
        {
            $_CONF = json_decode(file_get_contents('../config/config.json'));
        }
        $this->usuarios = $_CONF->usuario;
    }
    public function getNameByUser($user)
    {
        $num = $this->retrieveRolByName($user);
        return $this->getName(intval($num));
    }
    public function getName($num)
    {
        return $this->array[$num];
    }
    public function getAccessArticle($id,$clase_json)
    {
        $t_autor = explode(",",$clase_json->autor);
        foreach($t_autor as $autor)
        {
            $rol = $this->retrieveRolByName($autor);
            $rol_iniciado = $this->retrieveRolByName($_SESSION["usuario"]["user"]);
            if($autor == $_SESSION["usuario"]["user"])
                return true;
            else if($rol < $rol_iniciado)
            {
                return true;
            }
        }
        return false;
    }

    public function getAccessPage($id,$clase_json)
    {
        $t_autor = explode(",",$clase_json->autor);
        foreach($autor as $t_autor)
        {
            $rol = $this->retrieveRolByName($autor);
            $rol_iniciado = $this->retrieveRolByName($_SESSION["usuario"]["user"]);
            if($autor == $_SESSION["usuario"]["user"])
                return true;
            else if($rol < $rol_iniciado)
            {
                return true;
            }
        }
        return false;
    }

    public function RequestAccessSite($tabla)
    {
        $rol = 0;
        $nombre = "nothinglessnada";
        if(isset($_SESSION["usuario"]))
        {
            $rol = $this->retrieveRolByName($_SESSION["usuario"]["user"]);
            $nombre = $_SESSION["usuario"]["user"];
        }
        foreach($tabla as $linea)
        {
            if(is_numeric($linea))
            {
                if($rol >= $linea)
                    return true;
            }else
            {
                foreach($this->usuarios as $us)
                {
                    if($linea == $nombre)
                        return true;
                }
            }
        }
        return false;
    }

    public function DenyAccessSite($tabla)
    {
        $rol = 0;
        $nombre = "nothinglessnada";
        if(isset($_SESSION["usuario"]))
        {
            $rol = $this->retrieveRolByName($_SESSION["usuario"]["user"]);
            $nombre = $_SESSION["usuario"]["user"];
        }
        foreach($tabla as $linea)
        {
            if(is_numeric($linea))
            {
                if($rol <= $linea)
                    return false;
            }else
            {
                foreach($this->usuarios as $us)
                {
                    if($linea == $nombre)
                        return false;
                }
            }
        }
        return true;
    }

    public function retrieveRolByName($name)
    {
        foreach($this->usuarios as $linea)
        {
            if($linea->nombre == $name)
            {
                return $linea->rol;
            }
        }
    }
}