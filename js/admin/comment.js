function EliminarComment()
{
    var obj =
    {
        "article":$(this).parent().attr("idarticle"),
        "comment":$(this).parent().attr("idcomment")
    };
    var json = JSON.stringify(obj);
    $.post("api/",{del_comment:json}).done(function(info)
    {
        if(info)
            alert("Comentario eliminado con éxito");
        Comentar();
    });
}
function ApproveComment()
{
    var obj =
    {
        "article":$(this).parent().attr("idarticle"),
        "comment":$(this).parent().attr("idcomment")
    };
    var json = JSON.stringify(obj);
    $.post("api/",{approve_comment:json}).done(function(info)
    {
        if(info)
            alert("Comentario aprobado con éxito");
        Comentar();
    });    
}
function EliminarCommentMod()
{
    var obj =
    {
        "article":$(this).parent().attr("idarticle"),
        "comment":$(this).parent().attr("idcomment")
    };
    var json = JSON.stringify(obj);
    $.post("api/",{del_comment_mod:json}).done(function(info)
    {
        if(info)
            alert("Comentario eliminado con éxito");
        Comentar();
    });
}
 function Comentar()
    {
         $.get("api/?Allarticle").done(function (data) {
             var cont = "<div class='comment'><h3>Comentarios</h3><div class='moderation'>Activar la Moderación: <select id='mod_comment'><option value='true'>Sí</option><option value='false'>No</option></select></div><br /><div class='anonimo'>Activar Comentarios para invitados: <select id='anom_comment'><option value='true'>Sí</option><option value='false'>No</option></select></div><br />";
             for(var art of JSON.parse(data))
             {
                cont += "<div class='accordion' id='"+art.id+"'><span class='titulo'>"+art.name+"</span><p class='parr'><span>Descripción: </span>"+art.description+"</p><p class='parr'><span>Fecha: </span>"+art.date+"</p><div class='accordion'><h4>Comentarios a Moderar:</h4><div class='commentmodo"+art.id+"'></div></div> <div class='accordion'><h4>Comentarios Visibles:</h4><div class='comment"+art.id+"'></div></div></div>";
             }
             $(".content").html(cont);
             $.get("api/?getComments").done(function (data)
             {
                var obj_t = JSON.parse(data);
                // Comentarios moderados
                for(var a = 0;a < obj_t[1].length;a++)
                {
                    //tipocomentario/articulo/n_com
                    var num =  obj_t[1][a].length+1;
                    var bool = true;
                    for(var b = 0;b < num;b++)
                    {
                        var html = $(".commentmodo"+(a+1)).html();
                        var code = obj_t[1][a][b] || "";
                        var txt = code;
                        if(code != "")
                        {
                            txt = "<div idarticle='"+(a+1)+"' idcomment='"+b+"' class='comment_container'><button title='Aprobar Comentario' class='revisar'><i class='fa fa-check' aria-hidden=\"true\"></i></button><button title='Eliminar Comentario' class='eliminar_mod'><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></button><br /><br /><div class='com'>"+code+"</div></div>";
                            bool = false;
                        }
                        $(".commentmodo"+(a+1)).html(html+txt)
                    }
                    var html = $(".commentmodo"+(a+1)).html();
                    if(bool)
                        $(".commentmodo"+(a+1)).html(html+"<p class='nothing'>Ningún comentario que moderar.</p>")
                }
                // Comentarios normales
                for(var a = 0;a < obj_t[0].length;a++)
                {
                    //tipocomentario/articulo/n_com
                    var num =  obj_t[0][a].length+1;
                    var bool = true;
                    for(var b = 0;b < num;b++)
                    {
                        var html = $(".comment"+(a+1)).html();
                        var code = obj_t[0][a][b] || "";
                        var txt = code;
                        if(code != ""){
                            txt = "<div idarticle='"+(a+1)+"' idcomment='"+b+"' class='comment_container'><button title='Eliminar Comentario'class='eliminar'><i class=\"fa fa-trash\" aria-hidden=\"true\" /></button><br /><div class='com'>"+code+"</div></div>";
                            bool = false;
                        }
                        $(".comment"+(a+1)).html(html+txt);
                    }
                    var html = $(".comment"+(a+1)).html();
                    if(bool)
                        $(".comment"+(a+1)).html(html+"<p class='nothing'>Ningún comentario para este articulo.</p>")
                }
                $("#mod_comment").on("change",function()
                {
                    $.post("api/",{set_moderation:$("#mod_comment").val()}).done(function(info){
                        if(info)
                        {
                            alert("Configuración de moderación cambiada");
                        }
                    });
                });
               $("#anom_comment").on("change",function()
                {
                    $.post("api/",{set_anonym:$("#anom_comment").val()}).done(function(info){
                        if(info)
                        {
                            alert("Configuración de comentarios anonimos cambiada");
                        }
                    });
                });
                $.get("api/?isModerated").done(function(data) 
                {
                    $("#mod_comment option[value="+data+"]").attr("selected","selected");
                });
                $.get("api/?canAnonymComment").done(function(data) 
                {
                    if(data==1)
                        data = "true";
                    else
                        data = "false";
                    $("#anom_comment option[value="+data+"]").attr("selected","selected");
                });
                $(".revisar").click(ApproveComment);
                $(".eliminar").click(EliminarComment);
                $(".eliminar_mod").click(EliminarCommentMod);
             });
         });
    }