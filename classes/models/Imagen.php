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
    public function getImageFullPath($num)
    {
        return "img/client/".$this->tabla[$num];
    }
    public function getAllImage()
    {
        return $this->tabla;
    }
    public function getAllImageFullPath()
    {
        $rutas = array();
        foreach($this->tabla as $ruta)
        {
            $rutas[] = "img/client/".$ruta;
        }
        return $rutas;
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
    public function setZipFile($archivo,$opcion="plugin")
    {
        $jsonExist = "plugin.json";
        $sourcePath = $archivo['zipFile']['tmp_name'];
        if(file_exists("img/client") !== false)
            $targetPath = "";
        else
            $targetPath = "../";

        if($opcion == "plugin"){
            $targetPath .= "plugin/".str_replace(".zip","",$archivo['zipFile']['name'])."/";
        }else if($opcion == "template"){
            $targetPath .= "themes/".str_replace(".zip","",$archivo['zipFile']['name'])."/";
            $jsonExist = "theme.json";
        }else{
            return setImage($archivo);
        }
        
        $archivo_zip = new ZipArchive();
        $archivo_zip->open($sourcePath);
        if($archivo_zip->locateName($jsonExist) !== False)
        {
            $res = $archivo_zip->extractTo($targetPath);
            $archivo_zip->close();
            return $res;
        }else
        {
            return false;
        }
    }
}