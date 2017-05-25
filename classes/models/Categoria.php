<?php
    class Categoria
    {
        private static $_CAT = "";
        private static function leerFichero()
        {
            if(file_exists("store/categoria.json"))
                Categoria::$_CAT = json_decode(file_get_contents("store/categoria.json"));
            else
                Categoria::$_CAT = json_decode(file_get_contents("../store/categoria.json"));
        }
        public static function setCategoria($texto)
        {
            $bool = false;
            Categoria::leerFichero();
            $nuevo_id = Categoria::getTotal();
            Categoria::$_CAT->$nuevo_id = $texto;
            if(file_exists("store/categoria.json"))
                $bool = file_put_contents("store/categoria.json",json_encode(Categoria::$_CAT));
            else
                $bool = file_put_contents("../store/categoria.json",json_encode(Categoria::$_CAT));
            return ($bool!==false);
        }
        public static function getTotal()
        {
            Categoria::leerFichero();
            $fichero = Categoria::$_CAT;
            $a = 1;
            foreach($fichero as $key=>$linea)
            {
                $a++;
            }
            return $a;
        }
        public static function getAllCategoria()
        {
            Categoria::leerFichero();
            $fichero = Categoria::$_CAT;
            $_tabla = array();
            foreach($fichero as $key=>$linea)
            {
                $_tabla[] = $key.",".$linea;
            }
            return $_tabla;            
        }
        public static function getCategoria($num)
        {
            Categoria::leerFichero();
            $fichero = Categoria::$_CAT;
            foreach($fichero as $key=>$linea)
            {
                //1:Divertimiento2:Acción3:Fantasia 
                if($key == $num)
                {
                    return $linea;
                }
            }
            return false;
        }
    }