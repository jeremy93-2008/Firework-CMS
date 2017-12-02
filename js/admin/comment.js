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
            toastInfo("Comentario eliminado con éxito");
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
            toastInfo("Comentario aprobado con éxito");
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
            toastInfo("Comentario eliminado con éxito");
        Comentar();
    });
}