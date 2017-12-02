function EliminarUser()
{
    var txt = ","+$(this).parent().attr("json");
    if(txt.indexOf("\"admin\"") == -1 && confirm("¿Está seguro de querer borrar este usuario?"))
    {
        $.post("api/", { delete_user: txt }).done(function(info)
            {
                if(info == "218")
                    toastInfo("Borrado exitosamente");
                Mostrar();
            });
    }else
    {
        if(txt.indexOf("\"admin\"") != -1)
            toastInfo("No se puede borrar el administrador del CMS");
    }
}
function CrearUser()
{
    $(".usuario").css("display","none");
    var usuario = $(".username").val()+","+$(".pass").val()+","+$(".pass").val()+","+$("#rol").val();
    $.post("api/", { add_user: usuario }).done(function(info)
            {
                if(info == "218")
                    toastInfo("Borrado exitosamente");
                Usuarios();
            });
}
function MostrarUser()
{
    $(".usuario").css("display","block");
}
function CerrarUser()
{
    $(".usuario").css("display","none");
}
/**
 * Convierte el numero del rol en nombre  
 */
function Rol(numero)
{
    var num = parseInt(numero);
    switch(num)
    {
        case 1: return "Usuario";
        case 2: return "Colaborador";
        case 3: return "Editor";
        case 4: return "Administrador";
    }
}