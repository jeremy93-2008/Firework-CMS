<?php 
class imagesSlide
{
    private $_ruta;
    private $_ruta_time;
    private $_tabla;
    private $_time;
    public function __construct()
    {
        $this->_ruta = Plugin::infoPlugin("Slideshow Plugin")->ruta."/store/imagen.ini";
        $this->_ruta_time = Plugin::infoPlugin("Slideshow Plugin")->ruta."/store/time.ini";
        if(file_exists($this->_ruta))
            $this->_tabla = explode(",",file_get_contents($this->_ruta));
        else
            $this->_tabla = array();
        if(file_exists($this->_ruta_time))
            $this->_time = file_get_contents($this->_ruta_time);
        else
            $this->_time = 5000;
    }
    public function getImages()
    {
        return $this->_tabla;
    }
    public function setImages($cadena)
    {
        $tabla = explode(",",$cadena);
        $this->_tabla = $tabla;
        $this->saveImages();
    }
    public function saveImages()
    {
        return (file_put_contents($this->_ruta,implode(",",$this->_tabla)) !== false);
    }
    public function setTime($num)
    {
        $this->_time = intval($num);
        $this->saveTime();
    }
    public function getTime()
    {
        return $this->_time;
    }
    public function saveTime()
    {
        return (file_put_contents($this->_ruta_time,$this->_time) !== false);
    }
}