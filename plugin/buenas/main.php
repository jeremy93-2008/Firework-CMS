<?php
class Slideshow
{
    function init()
    {
        echo "Mi primer plugin";
    }
    function reload()
    {
        echo "Recargando Página";
    }
    function afterMenu()
    {
        echo "Slideshow";
    }  
    function beforeArticle()
    {
        echo "antes";
    }
    function afterArticle()
    {
        echo "despues";
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