<?php
    class Comment
    {
        private $_comment_art_path;
        private $_comment_artmod_path;
        private $_comment_content;
        private $_comment_moderator;
        private $_usuarios;
        private $_id;
        public function __construct($id_art)
        {
            $this->_comment_art_path = "store/articulos/comment-".$id_art.".php";
            $this->_comment_artmod_path = "store/articulos/comment-".$id_art."mod.php";
            if(file_exists($this->_comment_art_path))
            {
                $this->_comment_content = file_get_contents($this->_comment_art_path);
            }else if(file_exists("../store/articulos/comment-".$id_art.".php"))
            {
                $this->_comment_art_path = "../store/articulos/comment-".$id_art.".php";
                $this->_comment_content = file_get_contents($this->_comment_art_path);
            }
            else
            {
                if(file_exists("../store/articulos"))
                    $this->_comment_art_path = "../store/articulos/comment-".$id_art.".php";
                $this->_comment_content = "<div class='list_comment list_comment-".$id_art."'>";
            }
            if(file_exists($this->_comment_artmod_path))
            {                
                $this->_comment_moderator = file_get_contents($this->_comment_artmod_path);
            }
            else if(file_exists("../store/articulos/comment-".$id_art."mod.php"))
            {         
                $this->_comment_artmod_path = "../store/articulos/comment-".$id_art."mod.php";       
                $this->_comment_moderator = file_get_contents($this->_comment_artmod_path);
            }
            else
            {
                if(file_exists("../store/articulos"))
                    $this->_comment_artmod_path = "../store/articulos/comment-".$id_art."mod.php";
                $this->_comment_moderator = "<div class='list_comment list_comment-".$id_art."'>";
            }
            $conf = "classes/models/comment/user.json";
            if(file_exists($conf))
                $this->_usuarios = json_decode(file_get_contents($conf));
            else
                $this->_usuarios = json_decode(file_get_contents("../".$conf));
            $this->_id = $id_art;
        }
        public function getComments()
        {
            return $this->_comment_content."</div>";
        }
        public function getCommentsModeration()
        {
            return $this->_comment_moderator."</div>";
        }
        public function getNumComment()
        {
           return count($this->getCommentAsArray());
        }
        public function saveCommentModeration()
        {
            return file_put_contents($this->_comment_artmod_path,$this->_comment_moderator);
        }
        public function saveComment()
        {
            return file_put_contents($this->_comment_art_path,$this->_comment_content);
        }
        public function getCommentAsArray()
        {
            $arr = array();
            $actual = strpos($this->_comment_content,"<!--Comment-->");
            while($actual !== False)
            {
                $nuevo = strpos($this->_comment_content,"<!--Comment-->",$actual+1);
                if($nuevo !== false)
                {
                    $longitud = $nuevo - $actual;
                    $arr[] = substr($this->_comment_content,$actual,$longitud);
                    $actual  = $nuevo;
                }else
                {
                    $arr[] = substr($this->_comment_content,$actual);
                    $actual  = $nuevo;                   
                }
            }
            return $arr;
        }
        public function getCommentModerationAsArray()
        {
            $arr = array();
            $actual = strpos($this->_comment_moderator,"<!--Comment-->");
            while($actual !== False)
            {
                $nuevo = strpos($this->_comment_moderator,"<!--Comment-->",$actual+1);
                if($nuevo !== false)
                {
                    $longitud = $nuevo - $actual;
                    $arr[] = substr($this->_comment_moderator,$actual,$longitud);
                    $actual  = $nuevo;
                }else
                {
                    $arr[] = substr($this->_comment_moderator,$actual);
                    $actual  = $nuevo;                   
                }
            }
            return $arr;
        }
        public function removeComment($num)
        {
            $arr = $this->getCommentAsArray();
            unset($arr[$num]);
            $this->_comment_content = implode($arr);
            $this->saveComment();
            return true;
        }
        public function removeCommentModeration($num)
        {
            $arr = $this->getCommentModerationAsArray();
            unset($arr[$num]);
            $this->_comment_moderator = implode($arr);
            $this->saveCommentModeration();
            return true;
        }
        public function setComment($num,$usuario,$texto)
        {
            $arr = $this->getCommentAsArray();
            $arr[$num] = "<!--Comment--><div class='com art_".$this->_id." com_".$this->getNumComment()."'><h4>".$usuario.":</h4><p>".$texto."</p></div>";
            $this->_comment_content = implode($arr);
            $this->saveComment();
        }
        public function addComment($usuario,$texto)
        {
            $tabla = $this->_usuarios;
            $tabla_user = $this->_usuarios->usuario;
            if($this->getAuthforAddComment($tabla_user) && !($this->isModerationActived($tabla)))
            {
                $this->_comment_content .= "<!--Comment--><div class='com com_".$this->getNumComment()."'><h4>".$usuario.":</h4><p>".$texto."</p></div>";
                $this->saveComment();
                return "<h3 class='com_notice'>Comentario Enviado</h3>";
            }else if($this->isModerationActived($tabla))
            {
                $this->_comment_moderator .= "<!--Comment--><div class='com com_".$this->getNumComment()."'><h4>".$usuario.":</h4><p>".$texto."</p></div>";
                $this->saveCommentModeration();
                return "<h3 class='com_notice'>Comentario Enviado, en espera de moderación</h3>";
            }
            else
            {
                return "<h3 class='com_notice'>Sin permiso para comentar</h3>";
            }
        }
        public function approveComment($num)
        {
            $arr = $this->getCommentAsArray();
            $arr_mod = $this->getCommentModerationAsArray();
            $arr[] = $arr_mod[$num];
            unset($arr_mod[$num]);
            $this->_comment_content = implode($arr);
            $this->_comment_moderator = implode($arr_mod);
            $this->saveComment();
            $this->saveCommentModeration();
            return true;
        }
        private function isModerationActived($tabla)
        {
            $moderacion = strtolower($tabla->moderacion);
            if($moderacion == "true")
                return true;
            else
                return false;
        }
        public function getModeracion()
        {
            $tabla = $this->_usuarios;
            $moderacion = strtolower($tabla->moderacion);
            return $moderacion;
        }
        public function setModerar($str)
        {
            $tabla = $this->_usuarios;
            $tabla->moderacion = $str;
            $this->usuarios = $tabla;
            $conf = "classes/models/comment/user.json";
            $bool = false;
            if(file_exists($conf))
                $bool = file_put_contents($conf,json_encode($tabla));
            else
                $bool = file_put_contents("../".$conf,json_encode($tabla));
            return ($bool!==false);
        }
        private function getAuthforAddComment($tabla)
        {
            $User = new Users();
            $usuario = $User->getCurrentUser();
            $nombre = "";
            if($usuario !== false)
                $nombre = $User->getCurrentUser()->nombre;
            else
                $nombre = "anonimo";
            foreach($tabla as $linea)
            {
                if($linea->nombre == $nombre)
                {
                    if(isset($linea->articulo))
                    {
                        $art_rest =  explode(",",$linea->articulo);
                        foreach($art_rest as $art)
                        {
                            if($art == $this->_id)
                            {
                                return false;
                            }
                        }
                    }else
                    {
                        return false;
                    }
                }
            }
            return true;
        }
    }