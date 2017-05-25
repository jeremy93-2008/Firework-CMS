<?php

class Theme_Custom
{
    private $theme_estilos;
    private $theme;
    public function __construct()
    {
        if(file_exists('config/config.json'))
        {
            $_CONF = json_decode(file_get_contents('config/config.json'));
        }else
        {
            $_CONF = json_decode(file_get_contents('../config/config.json'));
        }
        $this->theme = $_CONF->tema;
        $this->theme_estilos = json_decode(file_get_contents($this->theme."/css/style.css"),true);
    }
}