function EliminarUser()
{
    var txt = ","+$(this).parent().attr("json");
    if(txt.indexOf("\"admin\"") == -1 && confirm("¿Está seguro de querer borrar este usuario?"))
    {
        $.post("api/", { delete_user: txt }).done(function(info)
            {
                if(info == "218")
                    alert("Borrado exitosamente");
                Mostrar();
            });
    }else
    {
        if(txt.indexOf("\"admin\"") != -1)
            alert("No se puede borrar el administrador del CMS");
    }
}
function CrearUser()
{
    $(".usuario").css("display","none");
    var usuario = $(".username").val()+","+$(".pass").val()+","+$(".pass").val()+","+$("#rol").val();
    $.post("api/", { add_user: usuario }).done(function(info)
            {
                if(info == "218")
                    alert("Borrado exitosamente");
                Mostrar();
            });
}
function MostrarUser()
{
    $(".usuario").css("display","block");
}
function Mostrar()
{
     $.get("api/?users").done(function(data)
        {
            var cont = "<div class='users'><h3>Usuarios</h3><button class='add_user'>Añadir Usuario</button>";
            for(var usuario of JSON.parse(data))
            {
                cont += "<div json='"+JSON.stringify(usuario)+"' class='accordion'><span class='titulo'>"+usuario.nombre+"</span><div class='icon' title='Eliminar Página'><i class='fa fa-times delete' aria-hidden='true' /></div><p class='parr'><b>Contraseña:</b> "+usuario.contrasenia+"</p><p class='parr'><b>Rol:</b> "+Rol(usuario.rol)+"</p></div>";
            }
            cont += "</div><div class='usuario'><h4>Crear nuevo Usuario</h4><p><label>Nombre de Usuario: </label><input class='username' type='text' /></p><p><label>Contraseña: </label><input class='pass' type='password' /></p><p><label>Rol: </label><select id='rol'><option value='1'>Usuario</option><option value='2'>Colaborador</option><option value='3'>Editor</option><option value='4'>Administrador</option></select></p><button class='add_usuario'>Crear Usuario</button></div>";
            $(".content").html(cont)
            $(".accordion .icon").click(EliminarUser);
            $(".users .add_user").click(MostrarUser);
            $(".usuario .add_usuario").click(CrearUser);
        });
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