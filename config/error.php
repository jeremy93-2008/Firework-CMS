<?php
$tem = new Template();
$tem->setFolder($_CONF->tema);
$tem->setVar("fw_title",$_CONF->titulo);
$tem->showError();