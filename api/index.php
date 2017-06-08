<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config/autoload.php';
include 'REST.php';
if(!isset($_COOKIE["token_auth"]))
{
    if(isset($_REQUEST["username"]) && isset($_REQUEST["password"]) && file_exists("../config/config.json"))
    {
        $bool = REST::Authenticate($_REQUEST["username"],$_REQUEST["password"]);
        if($bool)
            echo json_encode("Usuario conectado con éxito");
        else
            echo json_encode("Usuario incorrecto");
    }else
    {
        echo json_encode("Necesitas registrarte como usuario y tener ese permiso para acceder a la API del Framework, así que tener Firework configurado");
    }
}else
{
    $token = hash("ripemd256",$_SESSION["usuario"]["user"]);
    $numUser = substr($_COOKIE["token_auth"],0,64);
    if($token == $numUser)
    {
        try
        {
            if(count($_GET) == 0 && count($_POST) == 0)
            {
                include "../doc/doc_api.html";
            }
            // All GET Params to Select Values
            include 'GET.php';
            // All POST Params to Insert,Modify,UNDONE[Delete] Values
            include 'POST.php';
        }
        catch(Exception $e)
        {
            echo json_encode("Un Error ha surgido, y no se puede devolver la peticion requerida");
        }
    }
}
