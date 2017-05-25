<?php 
class Estadistica
{
    private $_EST = "";
    public function __construct()
    {
        if(file_exists("config/estadistica.json"))
        {
            $this->_EST = json_decode(file_get_contents("config/estadistica.json"));
        }else
        {
            $this->_EST = json_decode(file_get_contents("../config/estadistica.json"));
        }
    }
    public function setNewStat($enlace)
    {
        $fecha = date("d/m/Y",strtotime("now"));
        $url = $enlace;
        $tablanueva = array("url"=>$url,"fecha"=>$fecha);
        $this->_EST->estadistica[] = $tablanueva;
        if(file_exists("config/estadistica.json"))
        {
            file_put_contents("config/estadistica.json",utf8_encode(json_encode($this->_EST)));
            return true;
        }else
        {
            file_put_contents("../config/estadistica.json",utf8_encode(json_encode($this->_EST)));
            return true;
        }
        return false;
    }
    public function getAll()
    {
        return json_encode($this->_EST->estadistica);
    }
    public function getFromDate($date)
    {
        $array = array();
        $time = $date;
        $tabla = $this->_EST->estadistica;

        foreach($tabla as $linea)
        {
            $timelin = $linea->fecha;
            if($time < $timelin)
            {
                $array[] = (array)$linea;
            }
        }
        return json_encode($array);
    }
}