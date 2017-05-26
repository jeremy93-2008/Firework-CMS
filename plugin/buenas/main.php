<?php
class Slideshow
{
    function beforeMenu()
    {
        echo "<div class='slide'></div>";
    }  
    function showAdminView()
    {
        echo "Esto es un panel de administración";
    }
    function otherFunction()
    {
        echo "Bienvenido a una función personalizada del plugin";
    }
}