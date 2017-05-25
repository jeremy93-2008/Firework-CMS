<?php
/**
 * Class for manage Themes in CMS
 */
class Theme
{
    private $themes_arr;
    private $carpeta;
    private $themes_arr_info;
    private $conf;
    public function __construct()
    {
        $lista = array();
        $this->carpeta = "themes/";
        if(file_exists("../themes"))
        {
            $this->carpeta = "../themes/";
            $lista = scandir("../themes");
            $this->themes_arr = $lista;
        }else
        {
            $lista = scandir("themes");
            $this->themes_arr = $lista;
        }
        if(file_exists('config/config.json'))
        {
            $_CONF = json_decode(file_get_contents('config/config.json'),true);
        }else
        {
            $_CONF = json_decode(file_get_contents('../config/config.json'),true);
        }
        $this->conf = $_CONF;
    }
    private function getThemesInfoCache()
    {
        $this->themes_arr_info = $this->getThemesInfo();
    }
    /**
     * Retrieve all themes information located in /themes folder
     * @return array
     */
    public function getThemesInfo()
    {
        $ret_arr = array();
        for($a = 3;$a < count($this->themes_arr);$a++)
        {
            $theme_path = $this->carpeta.$this->themes_arr[$a]."/theme.json";
            if(file_exists($theme_path))
            {
                $tabla = json_decode(file_get_contents($theme_path));
                $tabla->ruta = "themes\/".$this->themes_arr[$a];
                $ret_arr[] = $tabla;
            }
        } 
        return $ret_arr;
    }
    /**
     * Add a new theme from a zip file and copy it in /themes folder
     *
     * @param [$_FILES] $archivo
     * @return bool
     */
    public function addNewTheme($archivo)
    {
        $sourcePath = $archivo['theme_zip']['tmp_name'];
        $zip = new ZipArchive();
        $res = $zip->open($sourcePath);
        if($res===true){
            $bool = $zip->extractTo("theme/".$archivo['theme_zip']['name']);
            $zip->close();
            return $bool;
        }else
        {
            return false;
        }
    }
    /**
     * Retrieve one theme info based in its location in array
     *
     * @param [number] $num
     * @return array
     */
    public function getThemeInfo($num)
    {
        return $this->themes_arr_info[$num];
    }
    /**
     * Set Main Theme for your home page
     *
     * @param [number] $num
     * @return int
     */
    public function setMainTheme($text)
    {
        $this->conf["tema"] = $text;
        if(file_exists('config/config.json'))
        {
            file_put_contents("config/config.json",json_encode($this->conf));
        }else
        {
            file_put_contents("../config/config.json",json_encode($this->conf));
        }
        return true;
    }
}