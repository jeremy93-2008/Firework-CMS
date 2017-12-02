<?php
class Plugin
{
    private static $_clases;
    private static $_numPlugin;
    private static $_view;
    private static $_config;
    public function __construct()
    {
        $this->getJsonPluginFolder();
    }
    public static function errorhandling($num, $str, $file, $line, $context = null)
    {
        throw new Exception("Error de plugin");
    }
    public function setConfig($CONF)
    {
        Plugin::$_config = $CONF;
    }
    public function setView($view="")
    {
        Plugin::$_view = $view;
    }
    public static function hasPlugin()
    {
        return (count(Plugin::$_numPlugin)>0);
    }  
    private function getJsonPluginFolder()
    {
        Plugin::$_numPlugin = 0;
        $lista = "";
        $carpeta = "";
        if(file_exists("../plugin")){
            $lista = scandir("../plugin");
            $carpeta = "../plugin/";
        }else{
            $lista = scandir("plugin");
            $carpeta = "plugin/";
        }
        usort($lista, function($a, $b){
            if(file_exists("../plugin"))
                $folder = "../plugin/";
            else
                $folder = "plugin/";
            return filectime($folder.$a) > filectime($folder.$b);
        });
        for($num = 0;$num < count($lista);$num++)
        {
            $ruta = $carpeta.$lista[$num];
            
            if(($lista[$num] != "." || $lista[$num] != "..") && file_exists($ruta."/plugin.json"))
            {
                $json_original = json_decode(file_get_contents($ruta."/plugin.json"));
                $json = json_decode(str_replace("~",$ruta,file_get_contents($ruta."/plugin.json")));
                try
                {
                    set_error_handler("Plugin::errorhandling");
                    if(!isset($json))
                    {
                        $json = new stdClass();
                        $json->dependencia = [];
                        $json->adminDependencies = [];
                        $json->disabled = "true";
                    }
                    Plugin::$_clases[$json->name] = $json;
                    Plugin::$_clases[$json->name]->ruta = $ruta;
                    Plugin::$_clases[$json->name]->dependencia = $json->dependencies;
                    Plugin::$_clases[$json->name]->admindependencia = $json->adminDependencies;
                    if((!isset($json->disabled)) || (isset($json->disabled) && $json->disabled != "true"))
                    {
                        if(isset($json->mainClass) && array_search($json->mainClass,get_declared_classes()) === FALSE)
                        {
                            include_once $ruta."/".$json->file;
                            Plugin::$_clases[$json->name]->instance = new $json->mainClass();
                        }else
                        {
                            throw new Exception();
                        }
                        Plugin::$_numPlugin++;
                    }
                }catch(Exception $exp)
                {
                    $json_original->disabled = "true";
                    file_put_contents($ruta."/plugin.json",json_encode($json_original));
                    $_SESSION["ErrorPlugin"] = "Un Plugin ha dejado de funcionar correctamente y se ha desactivado, se recomienda su desinstalación o actualización.";
                }
                restore_error_handler();
            }
        }
    }
    /**
     * Función que sirve para recoger la información de los plugins instalados
     *
     * @param string $nombre el nombre del plugin
     * @return void
     */
    public static function infoPlugin($nombre="all")
    {
        if($nombre == "all")
        {
            $arr = [];
            foreach(Plugin::$_clases as $clase)
            {
                $arr[] = $clase;
            }
            return $arr;
        }
        else
        {
            return Plugin::$_clases[$nombre];
        }
    }
    /**
     * Función que permite saber si un plugin esta activado o no
     */
    public function isPluginExist($nombre)
    {
        if(isset(Plugin::$_clases[$nombre]) && isset(Plugin::$_clases[$nombre]->instance))
            return true;
        else
            return false;
    }
    /**
     * Función que permite desinstalar un plugin en concreto
     */
    public function desinstall($nombre)
    {
        $pl = Plugin::$_clases[$nombre];
        if(isset($pl->ruta))
        {
            return $this->delTree($pl->ruta);
        }
    }
    /**
     * Funcion que sirve para borrar un directorio de manera recursiva
     */
    public function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.','..')); 
        $str ="";
        foreach ($files as $file) { 
            $str = "$dir/$file";
          (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file"); 
        } 
        rmdir($dir);
        return true; 
    }
    /**
     * Función que permite activar o desactivar el plugin en cuestión
     */
    public function setDisabled($nombre)
    {
        $boo = $this->isPluginExist($nombre);
        if($boo)
        {
            Plugin::$_clases[$nombre]->instance = null;
            $json = json_decode(file_get_contents(Plugin::$_clases[$nombre]->ruta."/plugin.json"));
            $json->disabled = "true";
            file_put_contents(Plugin::$_clases[$nombre]->ruta."/plugin.json",json_encode($json));
            return false;
        }else
        {
            $json = json_decode(file_get_contents(Plugin::$_clases[$nombre]->ruta."/plugin.json"));
            if(isset($json->mainClass) && array_search($json->mainClass,get_declared_classes()) === FALSE)
            {
                include_once Plugin::$_clases[$nombre]->ruta."/".Plugin::$_clases[$nombre]->file;
            }
            Plugin::$_clases[$nombre]->instance = new $json->mainClass();
            unset($json->disabled);
            file_put_contents(Plugin::$_clases[$nombre]->ruta."/plugin.json",json_encode($json));
            return true;            
        }
    }
    /**
     * Función que sirve para añadir las dependencias solicitadas por el plugin en el json
     */
    public static function callPluginDependencies()
    {
        $jsandcss = "";
        if(isset(Plugin::$_clases) && Plugin::$_clases !== NULL)
        {
            foreach(Plugin::$_clases as $clase)
            {
                foreach($clase->dependencia as $each)
                {
                    if(strpos($each,".css"))
                    {
                        $jsandcss .= "<link name='plugin' rel='stylesheet' href='".$each."' />\n";
                    }else
                    {
                        $jsandcss .= "<script name='plugin' src='".$each."' type='text/javascript'></script>\n";
                    }
                }
            }
        }
        return $jsandcss;
    }
    /**
     * Función que sirve para añadir las dependencias solicitadas por el plugin en el json
     */
    public static function callAdminPluginDependencies()
    {
        $jsandcss = "";
        if(isset(Plugin::$_clases) && Plugin::$_clases !== NULL)
        {
            foreach(Plugin::$_clases as $clase)
            {
                foreach($clase->admindependencia as $each)
                {
                    if(strpos($each,".css"))
                    {
                        $jsandcss .= "<link name='plugin' rel='stylesheet' href='".$each."' />\n";
                    }else
                    {
                        $jsandcss .= "<script name='plugin' name='plugin' src='".$each."' type='text/javascript'></script>\n";
                    }
                }
            }
        }
        return $jsandcss;
    }
    /**
     * Función que sirve para crear una instancia de la clase principal de un plugin 
     * @param string $nombre el nombre del plugin
     * @return class Una instancia de la clase principal del plugin
     */
     public static function instanceClass($nombre)
     {
        if(isset(Plugin::$_clases[$nombre]))
        {
            if(isset(Plugin::$_clases[$nombre]->instance))
            {
                return Plugin::$_clases[$nombre]->instance;
            }
        }
        return "Este plugin se encuentra desactivado o no existe";
     }

    /** Hooks que permiten comunicar los plugins con la diferentes partes del CMS --> 26 Hooks */

    /**
     * Hook que ocurre al inicializarse los plugins por primera vez.
     *
     * @return void
     */
    public static function init()
    {
        if(!isset($_SESSION["init"]))
        {
            Plugin::execute("init");
            $_SESSION["init"] = "sesión";
        }else
        {
            Plugin::reload();
        }
    }
    public static function reload()
    {
        return Plugin::execute("reload");
    }
    public static function ready()
    {
        return Plugin::execute("ready");
    }
    public static function BeforeHeader()
    {
        return Plugin::execute("beforeHeader");
    }
    public static function AfterHeader()
    {
        return Plugin::execute("afterHeader");
    }
    public static function BeforeSideBar()
    {
        return Plugin::execute("beforeSideBar");
    }
    public static function AfterSideBar()
    {
        return Plugin::execute("afterSideBar");
    }
    public static function BeforeFooter()
    {
        return Plugin::execute("beforeFooter");
    }
    public static function AfterMenu()
    {
        return Plugin::execute("afterMenu");
    }
    public static function BeforeMenu()
    {
        return Plugin::execute("beforeMenu");
    }
    public static function AfterLoginForm()
    {
        return Plugin::execute("afterLoginForm");
    }
    public static function BeforeLoginForm()
    {
        return Plugin::execute("beforeLoginForm");
    }
    public static function AfterRegisterForm()
    {
        return Plugin::execute("afterRegisterForm");
    }
    public static function BeforeRegisterForm()
    {
        return Plugin::execute("beforeRegisterForm");
    }
    public static function AfterArticle()
    {
        return Plugin::execute("afterArticle");
    }
    public static function BeforeArticle()
    {
        return Plugin::execute("beforeArticle");
    }
    public static function AfterComment()
    {
        return Plugin::execute("afterComment");
    }
    public static function BeforeComment()
    {
        return Plugin::execute("beforeComment");
    }
    public static function AfterPage()
    {
        return Plugin::execute("afterPage");
    }
    public static function BeforePage()
    {
        return Plugin::execute("beforePage");
    }
    public static function AfterFooter()
    {
        return Plugin::execute("afterFooter");
    }
    public static function BeforeView()
    {
        return Plugin::execute("beforeView");
    }
    public static function AfterView()
    {
        return Plugin::execute("afterView");
    }
    public static function Logging()
    {
        return Plugin::execute("logging");
    }
    public static function Registering()
    {
        return Plugin::execute("register");
    }
    public static function AdminView()
    {
        return Plugin::execute("showAdminView");
    }
    public static function setDependenciesForAdmin()
    {
        return Plugin::execute("inHeadAdmin");
    }
    /**
     * Ejecuta las funciones en los Plugin según su Hook
     *
     * @param [type] $name nombre de la funcion
     * @return void
     */
    private static function execute($name)
    {
        $t_res = "";
        $b = 0;
        if(isset(Plugin::$_clases) && Plugin::$_clases !== NULL)
        {
            foreach(Plugin::$_clases as $obj)
            {
                if(isset($obj->instance))
                {
                    $conector = $obj->instance;
                    if(method_exists($conector,$name))
                    {
                        set_error_handler("Plugin::errorhandling");
                        try
                        {
                            ob_start();
                            $conector->$name();
                            $res = ob_get_contents();
                            ob_end_clean();
                            if($name=="showAdminView"){
                                $t_res .= "<div idPlugin=".$b." class='contenedorPlugin plugin admin'>".$res."</div>";
                                $b++;
                            }else{
                                $t_res .= $res;
                            }
                        }catch(Exception $exp)
                        {
                            $json_original = json_decode(file_get_contents($obj->ruta."/plugin.json"));
                            $json_original->disabled = "true";
                            file_put_contents($obj->ruta."/plugin.json",json_encode($json_original));
                            $_SESSION["ErrorPlugin"] = "Un Plugin ha dejado de funcionar correctamente y se ha desactivado, se recomienda su desinstalación o actualización.";
                        }
                        restore_error_handler();
                    }else
                    {
                        if($name=="showAdminView"){
                            $t_res .= "<div idPlugin=".$b." class='contenedorPlugin plugin admin'>Este Plugin no dispone de panel de administración</div>";
                            $b++;
                        }
                    }
                }else
                {
                    if($name=="showAdminView")
                    {
                        $t_res .= "<div idPlugin=".$b." class='contenedorPlugin plugin admin'>Este Plugin se encuentra desactivado y no se puede usar.</div>";
                        $b++;
                    }
                }
            }
        }
        return $t_res;
    }
    /**
     * Funciones de acceso a la información del CMS para los Plugins
     */

    /**
     * CRUD para Articulos
     *
     * @param string $id numero del articulo
     * @param string $operacion operacion deseada
     * @param string $contenido json para guardar
     * @return void
     */
    public static function Article($id = "all",$operacion = "get",$contenido="json")
    {
        $a = new Article();
        if($id == "all")
        {
            switch ($operacion)
            {
                case "get": return $a->getAllArticle(); break;
            }
        }else
        {
            switch ($operacion)
            {
                case "get": return $a->getArticle(intval($id)); break;
                case "set": return $a->setArticle($contenido); break;
                case "del": return $a->removeArticle(intval($id));break;
            }
        }
    }
    /**
     * CRUD para Paginas
     *
     * @param string $id numero de la pagina
     * @param string $operacion operacion deseada
     * @param string $contenido json para guardar
     * @return void
     */ 
    public static function Page($id = "all",$operacion = "get",$contenido="json")
    {
        $p = new Page();
        if($id == "all")
        {
            switch ($operacion)
            {
                case "get": return $p->getAllPage(); break;
            }
        }else
        {
            switch ($operacion)
            {
                case "get": return $p->getPage(intval($id)); break;
                case "set": return $p->setPage($contenido); break;
                case "del": return $p->removePage(intval($id));break;
            }
        }
    }
    /**
     * CRUD para Imagen
     *
     * @param string $id numero de la pagina
     * @param string $operacion operacion deseada
     * @param string $contenido json para guardar
     * @return void
     */
    public static function Image($id = "all",$operacion = "get",$file="json")
    {
        $i = new Imagen();
        if($id == "all")
        {
            switch ($operacion)
            {
                case "get": return $i->getAllImageFullPath(); break;
            }
        }else
        {
            switch ($operacion)
            {
                case "get": return $i->getImageFullPath(intval($id)); break;
                case "set": return $i->setImage($file); break;
            }
        }
    }
    /**
     * Devuelve el archivo de vista cargado actualmente, por defecto es index
     *
     * @return String Nombre del Archivo
     */
    public static function View()
    {
        if(Plugin::$_view=="")
        {
            return "index.php";
        }else
        {
            return Plugin::$_view;
        }
    }
    /**
     * Devuelve los parametros GET pedidos
     * 
     * @param string $arg Argumento del GET a recuperar 
     * @return Contenido del Argumento GET
     */
    public static function Get($arg="all")
    {
        if($arg == "all")
        {
            return $_GET;
        }else
        {
            return $_GET[$arg];
        }
    }
    /**
     * Devuelve si hay un usuario logeado o no
     * @return boolean 
     */
    public static function isLogged()
    {
        $u = new Users();
        $user = $u->getCurrentUser();
        return ($user!==false);
    }
    /**
     * Devuelve el array del Usuario actual con todos sus datos
     * @return array usuario,contraseña en ripemd256 y rol
     */
    public static function CurrentUser()
    {
        $u = new Users();
        return $u->getCurrentUser();
    }
    /**
     * Logea un usuario en este CMS
     * @param string $username Nombre de Usuario
     * @param string $password Contraseña
     * @return boolean
     */
    public static function Login($username,$password)
    {
        $u = new Users();
        return $u->loginUser($username,$password);
    }
    /**
     * Registra un usuario en este CMS
     * @param string $username Nombre de Usuario
     * @param string $password Contraseña
     * @param string $password2 Contraseña2
     * @param number optional $rol Inserta un rol que quieres que tenga el usuario
     * @return boolean
     */
    public static function Register($username,$password,$password2,$rol=1)
    {
        $u = new Users();
        return $u->registerUser($username,$password,$password2,$rol);
    }
    /**
     * Información acerca del CMS
     * @param string $nombre
     * @return string or array
     */
    public static function WebsiteInfo($nombre="all")
    {
        if($nombre =="all")
        {
            return [Plugin::$_config->titulo,Plugin::$_config->descripcion,Plugin::$_config->palabras_claves,Plugin::$_config->tema];
        }else
        {
            return Plugin::$_config->$nombre;
        }
    }
}