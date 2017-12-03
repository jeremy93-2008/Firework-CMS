<?php
class Article
{
    public $name,$id,$description,$date,$category,$autor,$image,$access,$content,$deny;
    public function getAllArticle()
    {
        $tabla = array();
        if(file_exists("./store/articulos"))
            $files = scandir("./store/articulos");
        else
            $files = scandir("../store/articulos");
        foreach($files as $value)
        {
            if(strpos($value,"article-") !== false)
            {
                $value = str_replace("article-","",$value);
                $numero = str_replace(".php","",$value);
                $art = new Article();
                $tabla[] = $art->getArticle($numero);
            }
        }
        usort($tabla,function($a,$b)
        {
            $date1 = str_replace("/","-",$a->date);
            $date2 = str_replace("/","-",$b->date);
            return (strtotime($date1)>strtotime($date2))? -1:(strtotime($date1)==strtotime($date2))? ($a->id>$b->id)?-1:($a->id==$b->id)? 0 : 1 : 1;
        });
        return $tabla;
    }
    public function getRangeArticle($inicio=0,$cantidad=10)
    {
        $tabla = array();
        if(file_exists("./store/articulos"))
            $files = array_diff(scandir("./store/articulos"),array(".",".."));
        else
            $files = array_diff(scandir("../store/articulos"),array(".",".."));
        $numMax = $this->getNextId()-1;
        for($a = $inicio+2;$a < $cantidad+$inicio+2;)
        {
            $value = "";
            if(isset($files[$a]))
            {
                $value = $files[$a];
            }else
            {
                break;
            }
            if(strpos($value,"article-") !== false)
            {
                $value = str_replace("article-","",$value);
                $numero = str_replace(".php","",$value);
                $art = new Article();
                $tabla[] = $art->getArticle($numero);
                if($numero >= $numMax)
                    $a = $cantidad+$inicio+2;
                else
                    $a++;
            }
        }
        usort($tabla,function($a,$b)
        {
            $date1 = str_replace("/","-",$a->date);
            $date2 = str_replace("/","-",$b->date);
            return (strtotime($date1)>strtotime($date2))? -1:(strtotime($date1)==strtotime($date2))? ($a->id>$b->id)?-1:($a->id==$b->id)? 0 : 1 : 1;
        });
        return $tabla;
    }
    public function getNextId()
    {
        $num = 0;
        if(file_exists("./store/articulos"))
            $files = scandir("./store/articulos");
        else
            $files = scandir("../store/articulos");
        foreach($files as $value)
        {
            if(strpos($value,"article-") !== false)
            {
                $value = str_replace("article-","",$value);
                $numero = str_replace(".php","",$value);
                if($numero > $num)
                    $num = $numero;
            }
        }
        return $num+1;
    }
    public function getArticle($id)
    {
        $this->getMetaArticle($id);
        $r = new Rol();
        $booleano = $r->RequestAccessSite(explode(",",$this->access));
        $booleano2 = $r->DenyAccessSite(explode(",",$this->deny));
        if($booleano && $booleano2)
            if(file_exists("./store/articulos/article-".$id.".php"))
                $this->content = "./store/articulos/article-".$id.".php";
            else
                $this->content = "../store/articulos/article-".$id.".php";
        else
            $this->content = "sin_permiso";
        return $this;
    }
    public function getMetaArticle($id)
    {
        if(file_exists("./store/articulos/article-".$id.".php"))
            $comentario = @file_get_contents("./store/articulos/article-".$id.".php");
        else
            $comentario = @file_get_contents("../store/articulos/article-".$id.".php");
        if($comentario !== false)
        {
             preg_match("@\/\*(.*)\*\/@s",$comentario,$comentario);
            $comentario = $comentario[1];
            preg_match("/Fw-Name:(.+);/",$comentario,$this->name);
            $this->name = trim($this->name[1]);
            preg_match("/Fw-Description:(.+);/",$comentario,$this->description);
            $this->description = trim($this->description[1]);
            preg_match("/Fw-Date:(.+);/",$comentario,$this->date);
            $this->date = trim($this->date[1]);
            preg_match("/Fw-Category:(.+);/",$comentario,$this->category);
            $cat = trim($this->category[1]);
            $cat = Categoria::getCategoria(intval($cat));
            $this->category = $cat;
            preg_match("/Fw-Author:(.+);/",$comentario,$this->autor);
            $this->autor = trim($this->autor[1]);
            preg_match("/Fw-Image:(.+);/",$comentario,$this->image);
            if($this->image != null)
                $this->image = trim($this->image[1]);
            else
                $this->image = "";
            preg_match("/Fw-Access:(.+);/",$comentario,$this->access);
            if($this->access != null)
                $this->access = trim($this->access[1]);
            else
                $this->access = "0";
            preg_match("/Fw-Deny:(.+);/",$comentario,$this->deny);
            if($this->deny != null)
                $this->deny = trim($this->deny[1]);
            else
                $this->deny = "0";
            $this->id = $id;
        }else
        {
            throw new Exception();
        }
    } 
    public function setArticle($json)
    {
        $clase_json = json_decode($json);
        if($clase_json->image != "")
            $clase_json->image = "Fw-Image:".$clase_json->image.";";
        $txt = "<?php
/*
    Fw-Name: ".$clase_json->name.";
    Fw-Description: ".$clase_json->description.";
    Fw-Date: ".$clase_json->date.";
    Fw-Category: ".$clase_json->category.";
    Fw-Author: ".$clase_json->autor.";
    Fw-Access: ".$clase_json->access.";
    Fw-Deny: ".$clase_json->deny.";
    ".$clase_json->image."
*/
?>";
        $txt .= $clase_json->content;

        $num = $clase_json->id;

        $r = new Rol();
        $bArticle = $r->getAccessArticle($num,$clase_json);
        if($bArticle)
        {
            if(file_exists("../store/articulos"))
                $bool = file_put_contents("../store/articulos/article-".$num.".php",$txt);
            else
                $bool = file_put_contents("store/articulos/article-".$num.".php",$txt);
                
            if($bool===false){
                return false;
            }
            else{
                return true;
            }
        }else
        {
            return false;
        }
    }
    public function removeArticle($id)
    {
        $art = new Article($id);
        $clase_json = json_encode($art->getArticle($id));
        $r = new Rol();
        $bArticle = $r->getAccessArticle($id,json_decode($clase_json));
        if($bArticle)
        {
            $bool = unlink("../store/articulos/article-".$id.".php");
            if(file_exists("../store/articulos/comment-".$id.".php"))
            {
                unlink("../store/articulos/comment-".$id.".php");
            }
            return $bool;
        }else
        {
            return false;
        }
    }
}