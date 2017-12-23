<?php
    class REST
    {
        private static $token;
        public static function Authenticate($username,$password)
        {
            $cont = json_decode(file_get_contents("config/user.json"));
            if(REST::usuario($username,$cont->usuario) !== false)
            {
                $u = new Users();
                $res = $u->loginUser($username,$password);
                return $res;
            }else
            {
                return false;
            }
        }
        private static function usuario($nombre,$tabla)
        {
            foreach($tabla as $linea)
            {
                if($linea->nombre == $nombre)
                {
                    return true;
                }
            }
            return false;
        }
    }