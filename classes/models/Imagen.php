<?php
class Imagen
{
    private $tabla;
    public function __construct()
    {
        $this->tabla = array();
        if(file_exists("img/client") !== false)
            $this->tabla = scandir("img/client");
        else
            $this->tabla = scandir("../img/client");
        array_splice($this->tabla,0,2);
    }
    public function getImage($num)
    {
        return $this->tabla[$num];
    }
    public function getAllImage()
    {
        return $this->tabla;
    }
    public function setImage($archivo)
    {
        $sourcePath = $archivo['image']['tmp_name'];
        if(file_exists("img/client") !== false)
            $targetPath = "img/client/".$archivo['image']['name'];
        else
            $targetPath = "../img/client/".$archivo['image']['name'];
        return move_uploaded_file($sourcePath,$targetPath) ; 
    }
}