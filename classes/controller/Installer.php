<?php

class Installer
{
    private $name, $user, $folder,$descripcion,$pclaves;
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getconfig()
    {
        $con = "config/config.json";
        if (!file_exists($con))
            $con = "../config/config.json";
        $t_conf = json_decode(file_get_contents($con));
        return $t_conf;
    }
    public function getConfigPath()
    {
        $con = "config/config.json";
        if (!file_exists($con))
            $con = "../config/config.json";
        return $con;
    }
    public function setUser($user,$pass,$rol)
    {
        $this->user = $user.",".$pass.",".$rol;
    }
    public function setDescription($desc)
    {
        $this->descripcion = $desc;
    }
    public function setKeyword($key)
    {
        $this->pclaves = $key;
    }
    public function setTheme($carpeta)
    {
        $this->folder = $carpeta;
    }
    public function setconfig($json)
    {
        $con = "config/config.json";
        if (!file_exists($con))
            $con = "../config/config.json";
        if (file_put_contents($con,$json)!==false)
            return true;
        else
            return false;
    }
    public function writeconfig()
    {
        $usuario = explode(",",$this->user);

        $conf = array();
        $conf["titulo"] = $this->name;
        $conf["descripcion"] = $this->descripcion;
        $conf["palabras_claves"] = $this->pclaves;
        $conf["usuario"][] = array("nombre"=>$usuario[0],"contrasenia"=>$usuario[1],"rol"=>$usuario[2]);
        $conf["tema"] = $this->folder;
        $fichero = json_encode($conf);
        $con = "config/config.json";
        if (file_exists($con))
            $con = "../config/config.json";
        $val = file_put_contents($con,$fichero);
        if($val===false)
            return false;
        else
            return true;
    }
}