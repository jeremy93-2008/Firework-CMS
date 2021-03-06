<?php
class Template
{
    private $folder = "classes/view/index";
    private $variable = array();
    private $user;
    private $article,$categoria,$page,$rol,$iniciado;
    public function __construct()
    {
        if(file_exists("config/config.json"))
        {
            $this->user = new Users();
            $this->article = new Article();
            $this->categoria = new Categoria();
            $this->page = new Page();
            $this->rol = new Rol();
        }
    }
    public function __get($name)
    {
        foreach($this->variable as $key=>$value)
        {
            if($key == $name)
            {
                return $value;
            }
        }
    }
    public function __set($name,$value)
    {
        $this->setVar($name,$value);
    }
    public function setFolder($name)
    {
        $this->folder = str_replace("\/","/",$name);
    }
    public function getFolder()
    {
        return $this->folder;
    }
    public function getThemePath()
    {
        return $this->getFolder();
    }
    public function setVar($name,$value)
    {
        $this->variable[$name] = $value;
    }
    public function setVarArray($tabla)
    {
        foreach($tabla as $key => $value)
        {
            $this->variable[$key] = $value;
        }
    }
    public function showHeader()
    {
        $page = $this;
        echo Plugin::BeforeHeader();
        $theme_custom = new Theme_Custom();
        if($theme_custom->isThemeCustomEnabled())
                echo "<link rel='stylesheet' href='css/custom.css' />";
        include $this->folder."/header.php";
        echo Plugin::AfterHeader();
    }
    public function showSideBar()
    {
        $page = $this;
        echo Plugin::BeforeSideBar();
        include $this->folder."/sidebar.php";
        echo Plugin::AfterSideBar();
    }
    public function showFooter()
    {
        $page = $this;
        echo Plugin::BeforeFooter();
        include $this->folder."/footer.php";
        echo Plugin::AfterFooter();
    }
    public function showError()
    {
        $page = $this;
        include $this->folder."/404.php";
    }
    public function showIndex()
    {
        $page = $this;
        $this->showView("index");
    }
    public function showView($pagina)
    {
        echo Plugin::BeforeView();
        $page = $this;
        $this->fw_show = $page;
        if(file_exists($this->folder."/$pagina.php"))
        {
            ob_start();
            include $this->folder."/$pagina.php";
            if(isset($_GET["admin"]) == false)
                $inicial = htmlspecialchars_decode(Plugin::callPluginDependencies());
            else
                $inicial= htmlspecialchars_decode(Plugin::callAdminPluginDependencies());
            $contenido = str_replace("<head>","<head>\n".$this->fw_afterHeader."\n",ob_get_contents());
            $contenido = str_replace("</head>",$this->fw_afterCloseHeader.$inicial."\n</head>",$contenido);
            $contenido = str_replace("<body>","<body>\n".$this->fw_afterBody,$contenido);
            $contenido = str_replace("</body>",$this->fw_afterCloseBody."\n</body>",$contenido);
            ob_end_clean();
            echo html_entity_decode($contenido);
        }else
        {
            throw new Exception();
        }
        echo Plugin::AfterView();
    }
    public function showArticleFromParam($tabla,$contenido=array(),$enlace=false)
    {
        $page = $this;
        if(isset($_GET["ac"]))
            return $this->showArticle($tabla,$contenido);
        else
            return $this->showAllArticles($tabla,$contenido,$enlace);
    }
    public function showArticleRangeFromParam($tabla,$cantidad=10,$contenido=array(),$enlace=false)
    {
        $page = $this;
        if(isset($_GET["ac"]))
            return $this->showArticle($tabla,$contenido);
        else
            return $this->showRangeArticles($tabla,$cantidad,$contenido,$enlace);
    }
    public function showArticle($tabla,$contenido=array(),$nocomment=false)
    {
        $html = Plugin::BeforeArticle();
        $page = $this;
        $html .= "<div class='art_single'>";
        //$tabla contiene todos los valores de las variables que quieres ver.
        $art = $this->fw_article;
        if($art->content !== "sin_permiso")
        {
           $ver = 0;
           $ok_content = false;
                foreach($tabla as $campo)
                {
                    if($campo == "content")
                    {
                        ob_start();
                        include $art->$campo;
                        $art->$campo = ob_get_contents();
                        ob_end_clean();
                    }
                    $nuevo = $art->$campo;
                    if(count($contenido) > 0){
                        if((isset($contenido[$ver]))){
                            if($contenido[$ver] == "" || $nuevo == $contenido[$ver] || strpos($nuevo,$contenido[$ver]) !== False){
                                $html .= "<div class='art_$campo'>".$art->$campo."</div>";
                                $ok_content = true;
                            }else
                            {
                                $ok_content = false;
                            }
                        }else
                        {
                            if($ok_content)
                                $html .= "<div class='art_$campo'>".$art->$campo."</div>";
                        }
                    }else{
                        $html .= "<div class='art_each art_$campo'>".$art->$campo."</div>";}
                    $ver++;
                }
                if(count($contenido) == 0 || $ok_content)
                    $html .= $this->showComments($art->id);
        }else
        {
            $html .= "<div class='access_denied'>Sin permiso para visualizar este contenido</div>";
        }
        $html .= "</div>";
        $html .= Plugin::AfterArticle();
        return $html;
    }
    public function showRangeArticles($tabla,$cantidad=10,$contenido=array(),$enlace=false,$nocomment=false)
    {
        $page = $this;
        $html = "<div class='art_group'>";
        $tamanio_total = count($this->fw_articles);
        $paginas_total = intval(intval($tamanio_total/$cantidad)+($tamanio_total%$cantidad!=0?1:0));
        //$tabla contiene todos los valores de las variables que quieres ver.
        $inicio = 0;
        if(isset($_GET["i"]))
            $inicio = intval($_GET["i"]);
        $articulos = $this->article->getRangeArticle($inicio,$cantidad);
        foreach($articulos as $art)
        {
            $htmlin = Plugin::BeforeArticle()."<div class='art_inside art_single'>";
            if($art->content !== "sin_permiso")
            {
                $ver = 0;
                $ok_content = false;
                foreach($tabla as $campo)
                {
                    if($campo == "content")
                    {
                        ob_start();
                        include $art->$campo;
                        $art->$campo = ob_get_contents();
                        ob_end_clean();
                    }
                    $nuevo = $art->$campo;
                    if(count($contenido) > 0){
                        if((isset($contenido[$ver]))){
                            if($contenido[$ver] == "" || $nuevo == $contenido[$ver] || strpos($nuevo,$contenido[$ver]) !== False){
                                if($campo == "name" && $enlace)
                                {
                                    $htmlin .= "<div class='art_each art_$campo'><a href='?pa=1&ac=".$art->id."'>".$art->$campo."</a></div>";
                                }else
                                {
                                    $htmlin .= "<div class='art_each art_$campo'>".$art->$campo."</div>";
                                }
                                $ok_content = true;
                            }else
                            {
                                $ok_content = false;
                            }
                        }else
                        {
                            if($ok_content)
                                if($campo == "name" && $enlace)
                                {
                                    $htmlin .= "<div class='art_each art_$campo'><a href='?pa=1&ac=".$art->id."'>".$art->$campo."</a></div>";
                                }else
                                {
                                    $htmlin .= "<div class='art_each art_$campo'>".$art->$campo."</div>";
                                }
                        }
                    }else{
                            if($campo == "name" && $enlace)
                            {
                                $htmlin .= "<div class='art_each art_$campo'><a href='?pa=1&ac=".$art->id."'>".$art->$campo."</a></div>";
                            }else
                            {
                                $htmlin .= "<div class='art_each art_$campo'>".$art->$campo."</div>";
                            }
                        }
                    $ver++;
                }
                if(count($contenido) == 0 || $ok_content)
                    if(!$nocomment)
                        $htmlin .= $this->showComments($art->id)."</div>".Plugin::AfterArticle();
                    else
                        $htmlin .= "</div>".Plugin::AfterArticle();
                else
                    $htmlin = "";
                    $html .= $htmlin;
            }else
            {
                $html .= "<div class='access_denied'>Sin permiso para visualizar este contenido</div>";
            }
        }
        $html .= "<div class='art_pagination'>";
        for($b = 0;$b < $paginas_total;$b++)
        {
            $url = preg_replace(array("/[?|&]i=\w/"),array(""),$_SERVER['REQUEST_URI']);
            $inicio2 = $b*$cantidad;
            $selecc = "";
            if($inicio==$inicio2)
                $selecc = " select_pagination";
            $html .= "<a class='num_pagination".$selecc."' href='".$url."&i=".$inicio2."'>".($b+1)."</a>";
        }
        $html .= "</div></div>";
        return $html;
    }
    public function showAllArticles($tabla,$contenido=array(),$enlace=false,$nocomment=false)
    {
        $page = $this;
        $html = "<div class='art_group'>";
        //$tabla contiene todos los valores de las variables que quieres ver.
        $articulos = $this->fw_articles;
        foreach($articulos as $art)
        {
            $htmlin = Plugin::BeforeArticle()."<div class='art_inside art_single'>";
            if($art->content !== "sin_permiso")
            {
                $ver = 0;
                $ok_content = false;
                foreach($tabla as $campo)
                {
                    if($campo == "content")
                    {
                        ob_start();
                        include $art->$campo;
                        $art->$campo = ob_get_contents();
                        ob_end_clean();
                    }
                    $nuevo = $art->$campo;
                     if(count($contenido) > 0){
                        if((isset($contenido[$ver]))){
                            if($contenido[$ver] == "" || $nuevo == $contenido[$ver] || strpos($nuevo,$contenido[$ver]) !== False){
                                if($campo == "name" && $enlace)
                                {
                                    $htmlin .= "<div class='art_each art_$campo'><a href='?pa=1&ac=".$art->id."'>".$art->$campo."</a></div>";
                                }else
                                {
                                    $htmlin .= "<div class='art_each art_$campo'>".$art->$campo."</div>";
                                }
                                $ok_content = true;
                            }else
                            {
                                $ok_content = false;
                            }
                        }else
                        {
                            if($ok_content)
                                if($campo == "name" && $enlace)
                                {
                                    $htmlin .= "<div class='art_each art_$campo'><a href='?pa=1&ac=".$art->id."'>".$art->$campo."</a></div>";
                                }else
                                {
                                    $htmlin .= "<div class='art_each art_$campo'>".$art->$campo."</div>";
                                }
                        }
                    }else{
                            if($campo == "name" && $enlace)
                            {
                                $htmlin .= "<div class='art_each art_$campo'><a href='?pa=1&ac=".$art->id."'>".$art->$campo."</a></div>";
                            }else
                            {
                                $htmlin .= "<div class='art_each art_$campo'>".$art->$campo."</div>";
                            }
                        }
                    $ver++;
                }
                if(count($contenido) == 0 || $ok_content)
                    if(!$nocomment)
                        $htmlin .= $this->showComments($art->id)."</div>".Plugin::AfterArticle();
                    else
                        $htmlin .= "</div>".Plugin::AfterArticle();
                else
                    $htmlin = "";
                $html .= $htmlin;
            }else
            {
                $html .= "<div class='access_denied'>Sin permiso para visualizar este contenido</div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
    public function showPage($tabla,$contenido=array())
    {
        $html = Plugin::BeforePage();
        $page = $this;
        $html .= "<div class='page_single'>";
        //$tabla contiene todos los valores de las variables que quieres ver.
        $art = $this->fw_page;
        if($art->content !== "sin_permiso")
        {
            $ver = 0;
            $ok_content = false;
                foreach($tabla as $campo)
                {
                    if($campo == "content")
                    {
                        ob_start();
                        include $art->$campo;
                        $art->$campo = ob_get_contents();
                        ob_end_clean();
                    }
                    $nuevo = $art->$campo;
                    if(count($contenido) > 0){
                        if((isset($contenido[$ver]))){
                            if($contenido[$ver] == "" || $nuevo == $contenido[$ver] || strpos($nuevo,$contenido[$ver]) !== False){
                                $html .= "<div class='page_each page__$campo'>".$art->$campo."</div>";
                                $ok_content = true;
                            }else
                            {
                                $ok_content = false;
                            }
                        }else
                        {
                            if($ok_content)
                                $html .= "<div class='page_each page_$campo'>".$art->$campo."</div>";
                        }
                    }else{
                        $html .= "<div class='page_each page_$campo'>".$art->$campo."</div>";}
                    $ver++;
                }
                if(count($contenido) == 0 || $ok_content)
                    $html .= $this->showComments($art->id);
                $html .= "</div>";
        }else
        {
            $html .= "<div class='access_denied'>Sin permiso para visualizar este contenido</div>";
        }
        $html .= Plugin::AfterPage();
        return $html;
    }
    public function showAllPages($tabla,$contenido=array())
    {
        $page = $this;
        $html = "<div class='page_group'>";
        //$tabla contiene todos los valores de las variables que quieres ver.
        $articulos = $this->fw_pages;
        foreach($articulos as $art)
        {
            $htmlin = Plugin::BeforePage();
            $htmlin .= "<div class='page_inside page_single'>";
            $ok_content = false;
            if($art->content !== "sin_permiso")
            {
                $ver = 0;
                foreach($tabla as $campo)
                {
                    if($campo == "content")
                    {
                        ob_start();
                        include $art->$campo;
                        $art->$campo = ob_get_contents();
                        ob_end_clean();
                    }
                    $nuevo = $art->$campo;
                    if(count($contenido) > 0){
                        if((isset($contenido[$ver]))){
                            if($contenido[$ver] == "" || $nuevo == $contenido[$ver] || strpos($nuevo,$contenido[$ver]) !== False){
                                $htmlin .= "<div class='page_each page__$campo'>".$art->$campo."</div>";
                                $ok_content = true;
                            }else
                            {
                                $ok_content = false;
                            }
                        }else
                        {
                            if($ok_content)
                                $htmlin .= "<div class='page_each page_$campo'>".$art->$campo."</div>";
                        }
                    }else{
                        $htmlin .= "<div class='page_each page_$campo'>".$art->$campo."</div>";}
                    $ver++;
                }
                if(count($contenido) == 0 || $ok_content)
                    $htmlin .= $this->showComments($art->id)."</div>".Plugin::AfterArticle();
                else
                    $htmlin = "";
                $html .= $htmlin;

                $html .= "</div>";
            }else
            {
                $html .= "<div class='access_denied'>Sin permiso para visualizar este contenido</div>";
            }
            $html .= Plugin::AfterPage();
        }
        $html .= "</div>";
        return $html;
    }
    public function AuthPostLogin()
    {
        $u = $this->user;
        if(isset($_POST["login"]))
        {
            $bool = $u->loginUser($_POST["usuario"],$_POST["contrasenia"]);
            Plugin::Logging();
            if($bool)
                $this->iniciado = true;
            else
                $this->iniciado = false;
        }else
        {
            $this->iniciado = false;
        }
        if(isset($_GET["session"]) && $this->iniciado == false)
        {
            Users::closeUser();
        }
    }
    public function showUserLogin()
    {
        echo Plugin::BeforeLoginForm();
        $page = $this;
        if(isset($_POST["login"]) || isset($_SESSION["usuario"]) || isset($_GET["session"]))
        {
            if(isset($_POST["login"]))
            {
                if($this->iniciado)
                    return "<div class='login_grp'><div class='login_each'>".$_SESSION["usuario"]["user"]." <a href='?session=cerrar'>Cerrar Sesión</a></div></div>".Plugin::AfterLoginForm();
                else
                    return "<div class='login_grp'><div class='login_each'>Error: El usuario no es correcto.</div></div>".Plugin::AfterLoginForm();;
            }else if(isset($_GET["session"]))
            {
                return "<div class='close_session'>Cerrada Sesión con éxito</div><form class='login_grp' action='#' method='POST'>
                        <input type='hidden' name='login' value='1' />
                        <div class='login_each'><label for='usuario'>Nombre de Usuario: </label><input type='text' name='usuario' /></div>
                        <div class='login_each'><label for='contrasenia'>Contraseña: </label><input type='password' name='contrasenia' /></div>
                        <div class='login_each'><input type='submit' value='Enviar' /></div>
                    </form>".Plugin::AfterLoginForm();;
            }
            return "<div class='login_grp'><div class='login_each'>".$_SESSION["usuario"]["user"]." <a href='?session=cerrar'>Cerrar Sesión</a></div></div>".Plugin::AfterLoginForm();;
        }else
        {
            return "<form class='login_grp' action='#' method='POST'>
                        <input type='hidden' name='login' value='1' />
                        <div class='login_each'><label for='usuario'>Nombre de Usuario: </label><input type='text' name='usuario' /></div>
                        <div class='login_each'><label for='contrasenia'>Contraseña: </label><input type='password' name='contrasenia' /></div>
                        <div class='login_each'><input type='submit' value='Enviar' /></div>
                    </form>".Plugin::AfterLoginForm();
        }
    }
    public function showUserRegister()
    {
        echo Plugin::BeforeRegisterForm();
        $page = $this;
        if(isset($_POST["register"]))
        {
            $u = $this->user;
            if(isset($_POST["register"]) && !(isset($_SESSION["register_not_duplicate"])))
            {
                $bool = $u->registerUser($_POST["usuario"],$_POST["contrasenia"],$_POST["contrasenia2"]);
                Plugin::Registering();
                $_SESSION["register_not_duplicate"] = true;
                if($bool)
                    return "<div class='login_grp'><div class='login_each'>Se ha registrado con éxito</div></div>".Plugin::AfterRegisterForm();
                else
                    return "<div class='login_grp'><div class='login_each'>No se ha registrado correctamente.</div></div>".Plugin::AfterRegisterForm();
            }
        }else
        {
            unset($_SESSION["register_not_duplicate"]);
            return "<form class='register_grp' action='#' method='POST'>
                        <input type='hidden' name='register' value='1' />
                        <div class='register_each'><label for='usuario'>Nombre de Usuario: </label><input type='text' name='usuario' /></div>
                        <div class='register_each'><label for='contrasenia'>Contraseña: </label><input type='password' name='contrasenia' /></div>
                        <div class='register_each'><label for='contrasenia'>Confirmar Contraseña: </label><input type='password' name='contrasenia2' /></div>
                        <div class='register_each'><input type='submit' value='Enviar' /></div>
                    </form>".Plugin::AfterRegisterForm();
        }
    }
    public function showMenu($titulo = "")
    {
        echo Plugin::BeforeMenu();
        $page = $this;
        $menu = new Menu();
        $lista_item = $menu->getMenu();
        $html = "<div class='header_menu'><ul class='list_menu'>";
        $li = array();
        foreach($lista_item as $objeto)
        {
            if($titulo != "" && strpos($objeto["titulo"],$titulo) !== false)
                $li[$objeto["orden"]] = "<li class='item_menu'><a href='".$objeto["url"]."'>".$objeto["titulo"]."</a></li>";
            elseif($titulo == "")
                $li[$objeto["orden"]] = "<li class='item_menu'><a href='".$objeto["url"]."'>".$objeto["titulo"]."</a></li>";
        }
        ksort($li);
        $inside_html = implode($li);
        $html .= $inside_html."</ul></div>";
        return $html.Plugin::AfterMenu();
    }
    private function showComments($id)
    {
        echo Plugin::BeforeComment();
        $page = $this;
        $num = $this->fw_page->id;
        $com = new Comment($id);
        $comentarios = "<div class='art_comment'>";
        if(isset($_POST["comment"]) && !(isset($_SESSION["comment_not_duplicate"])))
        {
            if($_POST["comment_id"] == $id)
            {
                $comentarios .= $com->addComment($_POST["comment"],$_POST["comment_text"]);
            }
            $_SESSION["comment_not_duplicate"] = true;
        }else
        {
            unset($_SESSION["comment_not_duplicate"]);
        }
        $comentarios .= $com->getComments();
        if($this->user->getCurrentUser() !== false)
            $edicion = "<form action='#' class='comment_insertion' method='POST'><input type='hidden' name='comment_id' value='".$id."' /><div class='edition-comment'><input readonly name='comment' type='text' value='".$this->user->getCurrentUser()->nombre."' /><textarea id='com-edit' name='comment_text'></textarea><input type='submit' value='Enviar' /></div></form>".Plugin::AfterComment();
        else
            $edicion = "<form action='#' class='comment_insertion' method='POST'><input type='hidden' name='comment_id' value='".$id."' /><div class='edition-comment'><input name='comment' type='text' value='' /><textarea name='comment_text'></textarea><input type='submit' value='Enviar' /></div></form>";
        return $comentarios.$edicion."</div>".Plugin::AfterComment();
    }
    public function isHome()
    {
        if(count($_GET) == 0)
        {
            return true;
        }else
        {
            return false;
        }
    }
}