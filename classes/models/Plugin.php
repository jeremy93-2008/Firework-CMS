<?php
class Plugin
{
    private static $_clases;
    private static $_build;
    private static $_view;
    private static $_config;
    public function __construct()
    {
        $this->getJsonPluginFolder();
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
        return (count(Plugin::$_build)>0);
    }   
    private function getJsonPluginFolder()
    {
        $lista = "";
        $carpeta = "";
        if(file_exists("../plugin")){
            $lista = scandir("../plugin");
            $carpeta = "../plugin/";
        }else{
            $lista = scandir("plugin");
            $carpeta = "plugin/";
        }
        for($num = 2;$num < count($lista);$num++)
        {
            $ruta = $carpeta.$lista[$num];
            
            if(file_exists($ruta."/plugin.json"))
            {
                $json = json_decode(file_get_contents($ruta."/plugin.json"));
                Plugin::$_clases[$json->name] = $json;
                Plugin::$_clases[$json->name]->ruta = $ruta;
                include $ruta."/".$json->file;
                Plugin::$_build[$json->name] = new $json->mainClass();
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
     * Función que sirve para crear una instancia de la clase principal de un plugin 
     * @param string $nombre el nombre del plugin
     * @return class Una instancia de la clase principal del plugin
     */
     public static function instanceClass($nombre)
     {
        return Plugin::$_build[$nombre];
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
        foreach(Plugin::$_build as $conector)
        {
            if(method_exists($conector,$name))
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