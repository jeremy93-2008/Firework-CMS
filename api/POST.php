<?php
$_CONF = json_decode(file_get_contents("../config/config.json"));
$r = new Rol();
$rol = $r->retrieveRolByName($_SESSION["usuario"]["user"]);
if(isset($_POST["set_article"]))
{
    $article = $_POST["set_article"];
    $ar = new Article();
    $bool = $ar->setArticle($article);
    if($bool)
        echo "Articulo guardado exitosamente";
    else
        echo "Fallo a la hora de guardar el Articulo";
}else if(isset($_POST["set_page"]))
{
    $article = $_POST["set_page"];
    $ar = new Page();
    $bool = $ar->setPage($article);
    if($bool)
        echo "Página guardada exitosamente";
    else
        echo "Fallo a la hora de guardar la Página";
}else if(isset($_POST["del_article"]))
{
    $article = new Article();
    $bool = $article->removeArticle($_POST["del_article"]);
    if($bool)
        echo "Eliminación realizada con éxito";
    else
        echo "Un fallo se ha producido y no se pudo eliminar su contenido";
}
else if(isset($_POST["del_page"]))
{
    $page = new Page();
    $bool = $page->removePage($_POST["del_page"]);
        if($bool)
        echo "Eliminación realizada con éxito";
    else
        echo "Un fallo se ha producido y no se pudo eliminar su contenido";
}
else if(isset($_REQUEST["close"]))
{
    Users::closeUser();
    echo "Session closed";
}else if(isset($_POST["set_image"]))
{
    $i = new Imagen();
    return $i->setImage($_FILES);
}else if(isset($_POST["set_category"]))
{
    return Categoria::setCategoria($_POST["set_category"]);
}else if(isset($_POST["add_menu"]))
{
    $menu = new Menu();
    $json_add = json_decode($_POST["add_menu"]);
    $menu->addMenuItem($json_add->nombre,$json_add->orden,$json_add->url);
}else if(isset($_POST["set_menu"]))
{
    $menu = new Menu();
    $json_add = json_decode($_POST["add_menu"]);
    $menu->setMenuItem($json_add->nombre_antiguo,$json_add->nombre,$json_add->orden,$json_add->url);
}else if(isset($_POST["del_menu"]))
{
    $menu = new Menu();
    return $menu->removeMenuItem($_POST["del_menu"]);
}else if(isset($_POST["set_menu_json"]))
{
    $menu = new Menu();
    $menu->setMenuinJson($_POST["set_menu_json"]);
    echo "Menú guardado exitosamente";
}else if(isset($_POST["delete_user"]))
{
    $u = new Users();
    echo $u->removeUser($_POST["delete_user"]);
}
else if(isset($_POST["add_user"]))
{
    $tabla = explode(",",$_POST["add_user"]);
    $u = new Users();
    echo $u->registerUser($tabla[0],$tabla[1],$tabla[2],$tabla[3]);
}else if(isset($_POST["add_user_api_access"]))
{
    $u = new Users();
    echo $u->addAccessApi($_POST["add_user_api_access"]);
}
else if(isset($_POST["remove_user_api_access"]))
{
    $u = new Users();
    echo $u->removeAccessApi($_POST["remove_user_api_access"]);
}
else if(isset($_POST["set_config"]))
{
    $i = new Installer();
    echo $i->setconfig($_POST["set_config"]);
}
else if(isset($_POST["del_comment_mod"]))
{
    $obj = json_decode($_POST["del_comment_mod"]);
    $c = new Comment(intval($obj->article));
    echo $c->removeCommentModeration(intval($obj->comment));
}
else if(isset($_POST["del_comment"]))
{
    $obj = json_decode($_POST["del_comment"]);
    $c = new Comment(intval($obj->article));
    echo $c->removeComment(intval($obj->comment));
}
else if(isset($_POST["approve_comment"]))
{
    $obj = json_decode($_POST["approve_comment"]);
    $c = new Comment(intval($obj->article));
    echo $c->approveComment(intval($obj->comment));
}
else if(isset($_POST["set_moderation"]))
{
    $c = new Comment(1);
    echo $c->setModerar($_POST["set_moderation"]);
}
else if(isset($_POST["set_anonym"]))
{
    $c = new Comment(1);
    echo $c->setAnonimoCommentOption($_POST["set_anonym"]);
}
else if(isset($_POST["set_main_theme"]))
{
    $t = new Theme();
    echo $t->setMainTheme($_POST["set_main_theme"]);
}
else if(isset($_POST["set_theme_custom_enabled"]))
{
    $t = new Theme_Custom();
    return $t->setThemeCustomEnabled($_POST["set_theme_custom_enabled"]);
}
else if(isset($_POST["set_css_and_generate"]))
{
    $t = new Theme_Custom();
    return $t->generateCSSFromSave($_POST["set_css_and_generate"]);
}