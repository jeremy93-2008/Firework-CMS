<?php 
include 'config/autoload.php';
session_start();
if(file_exists('config/config.json'))
{
    $_CONF = json_decode(file_get_contents('config/config.json'));
    try
    {
        include 'config/general.php';
    }catch(Exception $e)
    {
        include 'config/error.php';
    }
}else
{
    include 'config/install/install.php';
}