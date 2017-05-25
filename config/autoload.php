<?php
function mi_autocargador($clase) {
    if(file_exists("classes/controller/$clase.php"))
    {
        include "classes/controller/$clase.php";
    }else
    {
        include "classes/models/$clase.php";
    }
}

spl_autoload_register('mi_autocargador');