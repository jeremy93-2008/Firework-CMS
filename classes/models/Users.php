<?php
    class Users
    {
        private $usuarios;
        private $archivo;
        private $fichero;
        private $current_user;
        public function __construct()
        {
            if(file_exists('config/config.json'))
            {
                $_CONF = json_decode(file_get_contents('config/config.json'));
                $this->fichero = file_get_contents('config/config.json');
            }else
            {
                $_CONF = json_decode(file_get_contents('../config/config.json'));
                $this->fichero = file_get_contents('../config/config.json');
            }
            $this->usuarios = $_CONF->usuario;
            $this->archivo = $_CONF;
        }
        public function __get($nombre)
        {
            if($nombre == "usuarios")
                return $this->usuarios;
            else
                return $this->current_user[$nombre];
        } 
        private function apiAccess($nombre)
        {
            if(file_exists("api/config/user.json"))
            {
                $cont = json_decode(file_get_contents("api/config/user.json"));
            }else
            {
                $cont = json_decode(file_get_contents("../api/config/user.json"));
            }
            $tabla = $cont->usuario;
            foreach($tabla as $linea)
            {
                if($linea->nombre == $nombre)
                {
                    return true;
                }
            }
            return false;
        }
        public function loginUser($name,$password)
        {
            foreach($this->usuarios as $linea)
            {
                if($linea->nombre == $name)
                {
                    if($linea->contrasenia == md5($password))
                    {
                        $_SESSION["usuario"] = array("user"=>$name,"password"=>md5($password));
                        if($this->apiAccess($_SESSION["usuario"]["user"]))
                            setcookie("token_auth",md5($name).md5($password),0,"/");
                        else
                            setcookie("token_auth", "", time()-3600,"/");
                        return true;
                    }
                }
            }
            return false;
        }
        public function registerUser($name,$password,$password2,$rol=1)
        {
            if($password == $password2)
            {
                foreach($this->usuarios as $linea)
                {
                    if($linea->nombre == $name)
                    {
                        return false;
                    }
                }
                $this->archivo->usuario[] = array("nombre"=>$name,"contrasenia"=>md5($password),"rol"=> strval($rol));
                $conf = "../config/config.json";
                if(file_exists("config/config.json"))
                    $conf = "config/config.json";
                $a = file_put_contents($conf,json_encode($this->archivo));
                if($a)
                    return true;
                else
                    return false;
            }
        }
        public function getCurrentUser()
        {
            if(isset($_SESSION["usuario"]))
            {
                foreach($this->usuarios as $linea)
                {
                    if($linea->nombre == $_SESSION["usuario"]["user"])
                    {
                        return $linea;
                    }
                }
            }else
            {
                return false;
            }
        }
        public function getUsers()
        {
            return $this->usuarios;
        }
        public function removeUser($json)
        {
            $nuevo = str_replace($json,"",$this->fichero);
            $conf = "../config/config.json";
            if(file_exists("config/config.json"))
                $conf = "config/config.json";
            return file_put_contents($conf,$nuevo);
        }
        public static function closeUser()
        {
            unset($_SESSION["usuario"]);
            unset($_COOKIE["token_auth"]);
            setcookie("token_auth", null, -1,"/");
        }
        public function addAccessApi($user)
        {
            $conf = "../api/config/user.json";
            if(file_exists("api/config/user.json"))
                $conf = "api/config/user.json";
            $t_conf = json_decode(file_get_contents($conf));
            $t_conf->usuario[] = array("nombre"=>$user);
            $a = file_put_contents($conf,json_encode($t_conf));
            if($a)
                return true;
            else
                return false;
        }
        public function listAccessApi()
        {
            $conf = "../api/config/user.json";
            if(file_exists("api/config/user.json"))
                $conf = "api/config/user.json";
            return json_decode(file_get_contents($conf));
        }
        public function removeAccessApi($json)
        {
            $conf = "../api/config/user.json";
            if(file_exists("api/config/user.json"))
                $conf = "api/config/user.json";
            $t_conf = file_get_contents($conf);
            $t_conf = str_replace($json,"",$t_conf); 
            $a = file_put_contents($conf,$t_conf);
            if($a)
                return true;
            else
                return false;
        }
    }