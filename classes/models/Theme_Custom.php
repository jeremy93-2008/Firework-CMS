<?php
/**
 * Clase que permite la personalización de los temas
*/
class Theme_Custom
{
    private $theme_estilos;
    private $css_save;
    private $css_model;
    private $css_save_path;
    private $css_file;
    public function __construct()
    {
        if(file_exists('config/csssave.json'))
        {
            $this->css_save = json_decode(file_get_contents('config/csssave.json'));
            $this->css_model = json_decode(file_get_contents('config/cssproperty.json'));
            $this->css_save_path = 'config/csssave.json';
            $this->css_file = "css/custom.css";
        }else
        {
            $this->css_save = json_decode(file_get_contents('../config/csssave.json'));
            $this->css_model = json_decode(file_get_contents('../config/cssproperty.json'));
            $this->css_save_path = '../config/csssave.json';
            $this->css_file = "../css/custom.css";
        }
    }
    /**
     * Funcion que permite saber si la poersonalización de temas está activada
     */
    public function isThemeCustomEnabled()
    {
        return ($this->css_save->activado=="true");
    }
    /**
     * Funcion que permite recuperar el modelo CSS en JSON
     */
    public function getCSSModel()
    {
        return json_encode($this->css_model);
    }
    /**
     * Funcion que permite recuperar los datos del CSS guardado
     */
    public function getCSSSave()
    {
        return json_encode($this->css_save);
    }
    /**
     * Funcion que permite habilitar o deshabilitar la personalización de tema
     */
    public function setThemeCustomEnabled($booleano)
    {
        if($booleano == "true")
        {
            $this->css_save->activado = "true";
        }else
        {
            $this->css_save->activado = "false";
        }
        return (file_put_contents($this->css_save_path,json_encode($this->css_save))!==false);
    }
    /**
     * Función que permite recuperar el JSON que contiene los datos del CSS que se va a guardar
     */
    private function setCSSSave($json)
    {
        $tabla = json_decode($json);
        $this->css_save->propiedades = (array)$tabla;
        return (file_put_contents($this->css_save_path,json_encode($this->css_save))!==false);
    }
    /**
     * Función que permite generar el CSS a partir de los datos guardados del JSON de las propiedades del CSS
    */
    public function generateCSSFromSave($json)
    {
        $bPrimero = false;
        $this->setCSSSave($json);
        $anterior_clase = "nonenothing";
        $cadena_style = "";
        $prop_CSS = $this->css_save->propiedades;
        foreach($prop_CSS as $reglas)
        {
            $valor = $reglas->value;
            if(strpos($valor,",") !== false)
                $valor = str_replace(","," ",$valor);
            if($bPrimero == false)
            {
                $cadena_style .= $reglas->class."{
                    ".$reglas->property.$valor.";";
                $bPrimero = true;
            }else if($anterior_clase == $reglas->class)
            {
                $cadena_style .= "
                ".$reglas->property.$valor.";";
            }else
            {
                $cadena_style .= "} 
                ".$reglas->class."{
                    ".$reglas->property.$valor.";";
            }
            $anterior_clase = $reglas->class;
        }
        $cadena_style .= "
        }";
        return (file_put_contents($this->css_file,$cadena_style)!==false);
    }
}