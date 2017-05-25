<?php
class Menu
{
    // [{"titulo":"Pagina 1","orden":"1","url":"?pa=1"},{"titulo":"Articulo 2","orden":"0","url":"?pa=1&ac=2"}]
    private $_enlace;
    public function __construct()
    {
        if(file_exists("store/menu.json"))
        {
            $this->_enlace = json_decode(file_get_contents("store/menu.json"),true);
        }else if(file_exists("../store/menu.json"))
        {
            $this->_enlace = json_decode(file_get_contents("../store/menu.json"),true);
        }else
        {
            throw new Error();
        }
    }
    public function getMenu()
    {
        return $this->_enlace;
    }
    public function setMenuinJson($json)
    {
        $path = "store/menu.json";
        if(!file_exists("store/menu.json"))
        {
            $path= "../store/menu.json";
        }
        return file_put_contents($path,$json);
    }
    public function addMenuItem($nombre,$orden,$url)
    {
        $this->_enlace[] = array("titulo"=>$nombre,"orden"=>$orden,"url"=>$url);
        $this->saveMenu();
        return true;
    }
    public function removeMenuItem($numero)
    {
        unset($this->_enlace[$numero]);
        $this->saveMenu();
        return true;
    }
    public function setMenuItem($nombre_busqueda,$nombre_nuevo,$orden,$url)
    {
        $a = 0;
        foreach($this->_enlace as $item)
        {
            if($item["titulo"] == $nombre_busqueda)
            {
                $this->_enlace[$a] = array($nombre_nuevo,$orden,$url);
                $this->saveMenu();
                return true;
            }
            $a++;
        }
        return false;
    }
    public function setMenuOption($nombre_b,$orden,$url)
    {
        $a = 0;
        foreach($this->_enlace as $item)
        {
            if($item["titulo"] == $nombre_b)
            {
                $this->_enlace[$a] = array($nombre_b,$orden,$url);
                $this->saveMenu();
                return true;
            }
            $a++;
        }
        return false;
    }
    private function saveMenu()
    {
        $path = "store/menu.json";
        if(!file_exists("store/menu.json"))
        {
            $path= "../store/menu.json";
        }
        return file_put_contents(path,json_encode($this->_enlace));
    }
}