var titulo = false;
var imageco = {
    id : 'Imagen',
    text : 'Añadir Archivo',
    icon: '../img/picture.png',
    action : function () {
        titulo = false;
        imagen();
    }
};
var instantiateTextbox = function () {
        textboxio.replaceAll('textarea', {
          paste: {
            style: 'clean'
          },
          ui: {
                toolbar: {
                // groups can be defined inline as part of the toolbar items
                items: ['undo','insert',{
                        label: 'Imagen',
                        items: [imageco]
                    }, 'style', 'emphasis', 'align', 'listindent', 'format', 'tools']
                }
             }
      });
};
function setEditorContent(html){
        var editors = textboxio.get('#contenido');
        var editor = editors[0];
        var txt = editor.content.get();
        editor.content.set(txt+html);
    };
function getEditorContent(){
        var editors = textboxio.get('#contenido');
        var editor = editors[0];
        return editor.content.get();
    };
function EliminarPag()
{
    if(confirm("¿Desea realmente eliminar esta página?"))
    {
        $.post("api/",{del_page: $(this).attr("pagina")}).done(function(info)
        {
            alert(info);
        });
        Paginas();
    }
}
function guardarPage()
{
    $.get("api/?pageNextId").done(function(data)
    {
         var f = new Date();
         var fecha = CeroIzq(f.getDate())+"/"+CeroIzq(f.getMonth()+1)+"/"+f.getFullYear();
         if($("#id_art").val() != "nuevo")
            data = parseInt($("#id_art").val());
        //set_page
        var json = 
        {
            "name":$(".title").val(),
            "description":$(".desc").val(),
            "image":$(".img_art").attr("src"),
            "content":getEditorContent(),
            "autor":$(".autor").val(),
            "keyword":$(".key").val(),
            "access":$(".acc").val(),
            "id":parseInt(data)
        }
        $.post("api/", { set_page: JSON.stringify(json) }).done(function(info)
        {
            alert(info);
        })
    });
}
function SelObjeto(img)
{
    if(img.indexOf(".jpg") != -1 || img.indexOf(".png") != -1 || img.indexOf(".gif") != -1 || img.indexOf(".bmp") != -1)
    {
        return "<img src='"+img+"' />";
    }
    else if(img.indexOf(".mp4") != -1 || img.indexOf(".avi") != -1 || img.indexOf(".webm") != -1 || img.indexOf(".ogv") != -1)
    {
        return "<video controls src='"+img+"' />";
    }
    else if(img.indexOf(".mp3") != -1 || img.indexOf(".ogg") != -1 || img.indexOf(".wav") != -1)
    {
        return "<audio controls src='"+img+"' />";
    }
    else if(img.indexOf(".pdf") != -1)
    {
        return "<iframe src='"+img+"' ></iframe>";
    }
    else
    {
        return "<a href='"+img+"'>"+img+"</a>";
    }
}
function insertarEditor()
{
    var img = SelObjeto($(this).attr("obj"));
    setEditorContent(img);
    cerrarImg();
}
function insertarDestacado()
{
    $(".img_art").attr("src",$(this).attr("src"));
    cerrarImg();
}
function annadirImg()
{
    titulo = true;
    imagen();
}
function cerrarImg()
{
    $(".uploadimg").remove();
}
function imagen()
{
    var contenedor = createEmmetNodes(".uploadimg>.header>h4.title{Medios}+span.cerrar_img{X}^.contenido>h4{Elige un Objeto}+.imagen+p{-o-}+h4{Sube un Archivo}+.subir>input[type=file,id=archivo]+i.fa.fa-upload.sube[aria-hidden=true,name=set_image]");
    $(".content").append(contenedor);
    $(".sube").click(subirImagenes);
    $(".cerrar_img").click(cerrarImg);
    $.get("api/?getImage").done(function(data)
    {
        $(".imagen").html("");
        for(var img of JSON.parse(data))
        {
            var antiguo = $(".imagen").html();
            var objeto = getRightObject(img);
            $(".imagen").html(antiguo+objeto);
            if(titulo)
                $(".imagen img").click(insertarDestacado);
            else
                $(".imagen img").click(insertarEditor);
        }
    });
}
function getRightObject(obj)
{
    if(obj.indexOf(".jpg") != -1 || obj.indexOf(".png") != -1 || obj.indexOf(".gif") != -1 || obj.indexOf(".bmp") != -1)
    {
        return "<img obj='img/client/"+obj+"' src='img/client/"+obj+"' style='width:128px;margin: 10px;' />";
    }
    else if(obj.indexOf(".mp4") != -1 || obj.indexOf(".avi") != -1 || obj.indexOf(".webm") != -1 || obj.indexOf(".ogv") != -1)
    {
        return "<img obj='img/client/"+obj+"' src='https://placeholdit.imgix.net/~text?txtsize=33&txt=Video&w=128&h=128' style='width:128px;margin: 10px;' />";
    }
    else if(obj.indexOf(".mp3") != -1 || obj.indexOf(".ogg") != -1 || obj.indexOf(".wav") != -1)
    {
        return "<img obj='img/client/"+obj+"' src='https://placeholdit.imgix.net/~text?txtsize=33&txt=Audio&w=128&h=128' style='width:128px;margin: 10px;' />";
    }
    else if(obj.indexOf(".pdf") != -1)
    {
        return "<img obj='img/client/"+obj+"' src='https://placeholdit.imgix.net/~text?txtsize=33&txt=PDF&w=128&h=128' style='width:128px;margin: 10px;' />";
    }
    else
    {
        return "<img obj='img/client/"+obj+"' src='https://placeholdit.imgix.net/~text?txtsize=33&txt=Objeto&w=128&h=128' style='width:128px;margin: 10px;' />";
    }
}
function subirImagenes()
{
    var form = new FormData();
    form.append("set_image","setimage");
    form.append("image",$("#archivo").prop("files")[0]);
    $.ajax({
            url: "api/?set_image", // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
                alert("Imagen súbida con éxito");
                $.get("api/?getImage").done(function(data)
                {
                    $(".imagen").html("");
                    for(var img of JSON.parse(data))
                    {
                        var antiguo = $(".imagen").html();
                        var objeto = getRightObject(img);
                        $(".imagen").html(antiguo+objeto);
                        if(titulo)
                            $(".imagen img").click(insertarDestacado);
                        else
                            $(".imagen img").click(insertarEditor);
                    }
                });
            }
        });
}
function InsertarRole()
{
    if($("#selec_rol").val() == "borrador")
    {
        $(".acc").val($("#user_id").html());
    }else
    {
        var accesos = $(".acc").val();
        var regex = new RegExp(/[0-9]/g);
        if(accesos == "")
        {
            $(".acc").val($("#selec_rol").val());
        }else if(regex.test(accesos))
        {
            var num = accesos.match(/[0-9]/g)[0];
            var nuevo = accesos.replace(num,$("#selec_rol").val());
            $(".acc").val(nuevo);
        }else
        {
            var antiguo = $(".acc").val();
            $(".acc").val(antiguo+","+$("#selec_rol").val());
        }
    }
}
function NuevoPageIndiv()
{
    var json = 
    {
        "name":"",
        "id":"nuevo",
        "description":"",
        "content":"",
        "autor":$("#usuario").val(),
        "keyword":"",
        "access":"0",
        "image":""
    };
    VerPageIndiv(json,"Crear");
}
function EditarPageIndiv()
{
    var usuario_actual = $("#user_id").html();
    var on = JSON.parse(this.getAttribute("alt"));
    if(on.content.indexOf("<?=$page") != -1)
    {
        if(on.name != "Los Articulos del Blog")
        {
            if(confirm("Esta página tiene funciones PHP especiales, si edita esta página se perderá estas funciones. ¿Está seguro que desea continuar?"))
            {
                 if(on.autor.indexOf(usuario_actual) !== -1)
                {
                    VerPageIndiv(on,"Editar");
                }else
                {
                    alert("No se permite editar esta página.");
                }
            }
        }else
        {
            alert("No se permite editar esta página.");
        }
    }
    else
    {
        if(on.autor.indexOf(usuario_actual) !== -1)
        {
            VerPageIndiv(on,"Editar");
        }else
        {
            alert("No se permite editar esta página.");
        }
    }
}
function VerPageIndiv(on,tittxt)
{       
    var fin = "<input type='hidden' id='id_art' value='"+on.id+"'><span>Titulo: </span><input class='title' type='text' value='"+on.name+"' /><br /><span>Descripción: </span><input class='desc' type='text' value='"+on.description+"' />";
    console.log(on);
    var f = new Date();
    var fecha = CeroIzq(f.getDate())+"/"+CeroIzq(f.getMonth()+1)+"/"+f.getFullYear();
    var comi = on.content.indexOf("<?php");
    var end = on.content.indexOf("?>",comi)+2;
    var reemplazar = on.content.substring(comi,end);
    on.content = on.content.replace(reemplazar,"");
    var img = "";
    if(on.image!="")
        img = "<img title='Haz clic para insertar una imagen...' class='img_art' height='150px' src='"+on.image+"' />";
    else
            img = "<img title='Haz clic para insertar una imagen...' class='img_art' height='150px' src='https://placeholdit.imgix.net/~text?txtsize=33&txt=Imagen&w=150&h=150' />";  
    
    var nuevo_acceso = "<select id='selec_rol'><optgroup label='Principales'><option value='0'>Todos</option><option value='borrador'>Borrador</option></optgroup><optgroup label='Roles'><option value='1'>Usuarios Registrados</option><option value='2'>Colaboradores</option><option value='3'>Editores</option><option value='4'>Administradores</option></optgroup></select>";

    fin += "<textarea id='contenido'>"+on.content+"</textarea><span title='Los autores son SOLO los usuarios que tienen acceso a la edición del articulo,\npueden ser uno o varios separados por comas, p.ej.: admin,gregorio' class='aut'>Autor: </span><input class='autor' type='text' style='width:150px' value='"+on.autor+"' /><span title='Acceso son los roles y usuarios que tienen acceso al articulo una vez publicado,\n para ello puede escribir manualmente los usuarios que tienen acceso\n o/y también puede seleccionar un rol completo de la lista desplegable.' class='acc_label'>Acceso: </span>"+nuevo_acceso+"<input title='Roles:\n0: Todos\n1: Usuarios Registrados\n2: Colaboradores\n3: Editores\n4: Administradores' class='acc' type='text' style='width:150px' value='"+on.access+"' /><div class='special_word'><span title='Las palabras claves que servirán al SEO de la página' class='word'>Keyword:</span><input type='text' class='key' value='"+on.keyword+"'/></div><span>Imagen: </span>"+img+"<br /><button class='guardar'>Guardar Página</button>";
    $(".content").html("");
    var txt = "<div class='articles'><h2 style='margin-top: 0px;padding-top: 5px;'>"+tittxt+" <sub style='font-size:12px'>Página - "+fecha+"</sub></h2>"+fin+"</div>";
    $(".content").html(txt);
    $(".guardar").click(guardarPage);
    var patt = new RegExp(/[0-9]/g);
    var res = patt.exec(on.access);
    $("#selec_rol option[value="+res+"]").attr("selected","selected");
    $("#selec_rol").on("change",function(){InsertarRole()});
    $(".img_art").click(annadirImg);
    instantiateTextbox();
}
/**
 * Añade un Cero a la izquierda del número cuando este es inferior a 10
 * @param {number} num 
 */
function CeroIzq($num)
{
    if($num<10)
        return "0"+$num;
    else
        return $num;
}