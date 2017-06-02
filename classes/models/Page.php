<?php
class Page
{
    public $name,$id,$description,$keyword,$image,$autor,$content;
    public function getAllPage()
    {
        $tabla = array();
        if(file_exists("./store/paginas_maestras"))
            $files = scandir("./store/paginas_maestras");
        else
            $files = scandir("../store/paginas_maestras");
        foreach($files as $value)
        {
            if(strpos($value,"page-") !== false)
            {
                $value = str_replace("page-","",$value);
                $numero = str_replace(".php","",$value);
                $art = new Page();
                $tabla[] = $art->getPage($numero);
            }
        }
        return $tabla;
    }
    public function getNextId()
    {
        $num = 0;
        if(file_exists("./store/paginas_maestras"))
            $files = scandir("./store/paginas_maestras");
        else
            $files = scandir("../store/paginas_maestras");
        foreach($files as $value)
        {
            if(strpos($value,"page-") !== false)
            {
                $value = str_replace("page-","",$value);
                $numero = str_replace(".php","",$value);
                if($numero > $num)
                    $num = $numero;
            }
        }
        return $num+1;
    }
    public function getPage($id)
    {
        $this->getMetaPage($id);
        $r = new Rol();
        $booleano = $r->RequestAccessSite(explode(",",$this->access));
        if($booleano)
            if(file_exists("./store/paginas_maestras/page-".$id.".php"))
                $this->content = "./store/paginas_maestras/page-".$id.".php";
            else
                $this->content = "../store/paginas_maestras/page-".$id.".php";
        else
            $this->content = "sin_permiso";
        return $this;
    }
    public function getMetaPage($id)
    {
        if(file_exists("./store/paginas_maestras/page-".$id.".php"))
            $comentario = @file_get_contents("./store/paginas_maestras/page-".$id.".php");
        else
            $comentario = @file_get_contents("../store/paginas_maestras/page-".$id.".php");
        if($comentario !== false)
        {
            preg_match("@\/\*(.*)\*\/@s",$comentario,$comentario);
            $comentario = $comentario[1];
            preg_match("/Fw-Name:(.+);/",$comentario,$this->name);
            $this->name = trim($this->name[1]);
            preg_match("/Fw-Description:(.+);/",$comentario,$this->description);
            $this->description = trim($this->description[1]);
            preg_match("/Fw-Author:(.+);/",$comentario,$this->autor);
            $this->autor = trim($this->autor[1]);
            preg_match("/Fw-Keywords:(.+);/",$comentario,$this->keyword);
            $this->keyword = trim($this->keyword[1]);
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
            $this->id = $id;
        }else
        {
            throw new Exception();
        }
    } 
    public function setPage($json)
    {
        $clase_json = json_decode($json);
        if($clase_json->image != "")
            $clase_json->image = "Fw-Image:".$clase_json->image.";";
        
        $txt = "<?php
/*
    Fw-Name: ".$clase_json->name.";
    Fw-Description: ".$clase_json->description.";
    Fw-Author: ".$clase_json->autor.";
    Fw-Access: ".$clase_json->access.";
    Fw-Keywords: ".$clase_json->keyword.";
    ".$clase_json->image."
*/
?>";
        $txt .= $clase_json->content;

        $num = $clase_json->id;

        $r = new Rol();
        $bPage = $r->getAccessPage($num,$clase_json);
        if($bPage){
            $bool = file_put_contents("../store/paginas_maestras/page-".$num.".php",$txt);
            if($bool===false){
                return false;
            }else{
                return true;
            }
        }else
        {
            return false;
        }
    }
    public function removePage($id)
    {
        $pag = new Page($id);
        $clase_json = json_encode($pag->getPage($id));
        $r = new Rol();
        $bPage = $r->getAccessPage($id,json_decode($clase_json));
        if($bPage)
        {
            $bool = unlink("../store/paginas_maestras/page-".$id.".php");
            return $bool;
        }else
        {
            return false;
        }
    }
}