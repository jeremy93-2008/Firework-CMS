<?php
$_CONF = json_decode(file_get_contents("../config/config.json"));
if(isset($_GET["current_user"]))
{
    echo json_encode($_SESSION["usuario"]["user"]);
}
else if(isset($_GET["users"]))
{
    $u = new Users();
    echo json_encode($u->getUsers());
}
else if(isset($_GET["current_user_role"]))
{
    $r = new Rol();
    echo json_encode($r->getNameByUser($_SESSION["usuario"]["user"]));
}else if(isset($_GET["current_theme"]))
{
    echo json_encode($_CONF->tema);
}
else if(isset($_GET["current_theme_more"]))
{
    $path = "../".$_CONF->tema."/theme.json";
    $contenido = file_get_contents($path);
    echo $contenido;
}
else if(isset($_GET["current_title"]))
{
    echo json_encode($_CONF->titulo);
}
else if(isset($_GET["statistic"])&&isset($_GET["date"]))
{
    $e = new Estadistica();
    echo $e->getFromDate($_GET["date"]);
}
else if(isset($_GET["statistic"]))
{
    $e = new Estadistica();
    echo $e->getAll();
}
else if(isset($_GET["page"]))
{
    $p = new Page();
    $todo = $p->getPage($_GET["page"]);
    if($todo->content == "sin_permiso")
    {
        echo json_encode("Sin permiso para ver esta página");
    }
    else
    {
        $todo->content = file_get_contents($todo->content);
        echo json_encode($todo);
    }
}
else if(isset($_GET["getImage"]))
{
    $i = new Imagen();
    if($_GET["getImage"] == "")
        echo json_encode($i->getAllImage());
    else
        echo json_encode($i->getImage($_GET["getImage"]));
}
else if(isset($_GET["article"]))
{
    $p = new Article();
    $todo = $p->getArticle($_GET["article"]);
    if($todo->content == "sin_permiso")
    {
        echo json_encode("Sin permiso para ver esta página");
    }
    else
    {
        $todo->content = file_get_contents($todo->content);
        echo json_encode($todo);
    }
}
else if(isset($_GET["pageNextId"]))
{
    $p = new Page();
    $todo = $p->getNextId();
    echo json_encode($todo);
}
else if(isset($_GET["articleNextId"]))
{
    $p = new Article();
    $todo = $p->getNextId();
    echo json_encode($todo);
}
else if(isset($_GET["getCategory"]))
{
    if($_GET["getCategory"] == "")
        echo json_encode(Categoria::getAllCategoria());
    else
        echo json_encode(Categoria::getCategoria(intval($_GET["getCategory"])));
}
else if(isset($_GET["Allpage"]))
{
    $p = new Page();
    $todo = $p->getAllPage();
    $a = true;
    foreach($todo as $pag)
    {
        if($pag->content == "sin_permiso")
           $a = false;
        else
           $pag->content = file_get_contents($pag->content);
    }
    if($a)
        echo json_encode($todo);
    else
        echo json_encode("Sin permisos para ver una de estas páginas");
}
else if(isset($_GET["Allarticle"]))
{
    $p = new Article();
    $todo = $p->getAllArticle();
    $a = true;
    foreach($todo as $pag)
    {
        if($pag->content == "sin_permiso")
           $a = false;
        else
           $pag->content = file_get_contents($pag->content);
    }
    if($a)
        echo json_encode($todo);
    else
        echo json_encode("Sin permisos para ver estas páginas");
}else if(isset($_GET["getMenu"]))
{
    $menu = new Menu();
    echo json_encode($menu->getMenu());
}
else if(isset($_GET["getMenuFull"]))
{
    $a = new Article();
    $a_todo = $a->getAllArticle();
    $p = new Page();
    $p_todo = $p->getAllPage();
    $menu = new Menu();
    $tabla = array($menu->getMenu(),$a_todo,$p_todo);
    echo json_encode($tabla);
}else if(isset($_GET["access_user_api"]))
{
    $u = new Users();
    echo json_encode($u->listAccessApi());
}
else if(isset($_GET["getConfig"]))
{
    $i = new Installer();
    echo json_encode($i->getconfig());
}else if(isset($_GET["getThemes"]))
{
    $th = new Theme();
    echo json_encode($th->getThemesInfo());
}else if(isset($_GET["getComments"]))
{
    $a = new Article();
    $getnextid = $a->getNextId();
    $comm = array();
    $commod = array();
    for($a = 1;$a < $getnextid;$a++)
    {
        $c = new Comment($a);
        $comm[] = $c->getCommentAsArray();
        $commod[] = $c->getCommentModerationAsArray();
    }
    echo json_encode(array($comm,$commod));
}else if(isset($_GET["isModerated"]))
{
    $c= new Comment(1);
    echo $c->getModeracion();
}