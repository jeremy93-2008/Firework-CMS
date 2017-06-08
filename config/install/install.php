<?php
$p = new Plugin();
$tem = new Template();
$tem->setFolder("classes/view/install");
$tem->setVar("fw_title","Instalador de Firework");
$tem->showIndex();