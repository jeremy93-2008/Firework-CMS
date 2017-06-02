<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//$_CONF --> fichero JSON
$p = new Plugin();
if(isset($_GET["co"]))
    $p->setView($_GET["co"]);
else
    $p->setView();
$p->setConfig($_CONF);
Plugin::init();

$tem = new Template();
if(isset($_GET["admin"])){
    $tem->setFolder("admin");
}else if(isset($_GET["ro"])){
    $tem->setFolder($_GET["ro"]);
    if(strpos($_GET["ro"],"admin") == false)
    {
        $tem->AuthPostLogin();
    }
}else{
    $tem->setFolder($_CONF->tema);
    $tem->AuthPostLogin();
}
$tem->setVar("fw_title",$_CONF->titulo);
$tem->setVar("fw_descripcion",$_CONF->descripcion);
$tem->setVar("fw_keywords",$_CONF->palabras_claves);
$art = new Article();
$tem->setVar("fw_articles",$art->getAllArticle());
$pag = new Page();
$tem->setVar("fw_pages",$pag->getAllPage());
$tem->setVar("fw_page",$pag->getPage(1));
$tem->setVar("fw_article",$art->getArticle(1));
if(isset($_GET["ac"]))
{
    $nuevo = new Article();
    $tem->setVar("fw_article",$art->getArticle($_GET["ac"]));
}
if(isset($_GET["pa"]))
{
    $tem->setVar("fw_page",$pag->getPage($_GET["pa"]));
}
if(isset($_GET["co"]))
{
    $tem->setVar("fw_show",ucfirst($_GET["co"]));
    $tem->showView($_GET["co"]);
}else
{
    $tem->setVar("fw_show",ucfirst("index"));
    $tem->showIndex();
}
if(!isset($_SESSION["stats"]))
    $_SESSION["stats"] = "";
    
if(strpos($_SESSION["stats"],$_SERVER["REQUEST_URI"]) === false)
{
    $e = new Estadistica();
    $e->setNewStat($_SERVER["REQUEST_URI"]);
    $_SESSION["stats"] .= $_SERVER["REQUEST_URI"].",";
}
echo Plugin::ready();
