<?php
class Slideshow
{
    /**
     * Include some files
     */
    function getPath()
    {
        return Plugin::infoPlugin("Slideshow Plugin")->ruta;
    }
    function includeCSS()
    {
        echo "<link rel='stylesheet' href='".$this->getPath()."/css/style.css' />";
    }
    function includeImageClass()
    {
        include $this->getPath()."/imagesSlide.php";
    }
    function includeJS()
    {
        echo "<script type='text/javascript' src='".$this->getPath()."/js/script.js' ></script>";
    }
    /**
     * Client View
     */
    function beforeMenu()
    {
        $this->includeImageClass();
        echo "<div class='slide'></div>";
    } 
    /**
     * Server View
     * */ 
    function showAdminView()
    {
        $this->includeCSS();
        $this->includeJS();
        $this->includeImageClass();
        echo "<div class='slideshow-main'><h2>Slideshow Plugin</h2>";
        echo "<p>Elige las imagenes que deseas incluir en el pase de diapositivas, en la lista de arriba <sub>(Para incluir nuevas imagenes, debe ir a el menu Medios, y añadir imagenes desde alli)</sub></p>";
        $this->getImage();
        echo "<p>Aqui se encuentran las imagenes seleccionadas para mostrarse en el pase de diapositivas</p>";
        $this->showCurrentImages();
        echo "<p>Elige el tiempo que debe pasar entre cada diapositiva</p>";
        $this->showTimer();
        echo "<p>Una vez seleccionado las imagenes, pulse sobre el botón de abajo</p>";
        echo "<button class='btn guardarSlideshow'>Guardar Lista elegida</button></div>";
    }
    function doPostFunction()
    {
        if(isset($_POST["datos"]) && isset($_POST["intervalo"]))
        {
            $lista_fichero = $_POST["datos"];
            $tiempo = $_POST["intervalo"];
            $ruta = Plugin::infoPlugin("Slideshow Plugin")->ruta."/store/imagen.ini";
            $ruta_time = Plugin::infoPlugin("Slideshow Plugin")->ruta."/store/time.ini";
            file_put_contents($ruta,$lista_fichero);
            file_put_contents($ruta_time,$tiempo);
            echo "Fichero guardado";
        }
    }
    function getImage()
    {
        $html = "<div class='file-image'><ul>";
        $imagenes = Plugin::Image();
        foreach($imagenes as $ruta)
        {
            if(strpos($ruta,".jpg") !== FALSE || strpos($ruta,".png") !== FALSE)
                $html .= "<li><img src='".$ruta."' />".$ruta."</li>";
        }
        $html .= "</ul></div>";
        echo $html;
    }
    function showCurrentImages()
    {
        $html = "<div class='slide-image'><ul>";
        $class = new imagesSlide();
        $imagenes = $class->getImages();
        foreach($imagenes as $ruta)
        {
            if(strpos($ruta,".jpg") !== FALSE || strpos($ruta,".png") !== FALSE)
                $html .= "<li><img src='".$ruta."' />".$ruta."</li>";
        }
        $html .= "</ul></div>";
        echo $html;
    }
    function showTimer()
    {
        $class = new imagesSlide();
        echo "<span>El intervalo de tiempo es de : </span><input type='number' id='timer' step='500' min='0' max='10000' value='".intval($class->getTime())."'/> ms";
    }
}