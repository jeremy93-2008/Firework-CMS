function anadirUsuario()
{
    var nombre = $("#userlist").val();
    $.post("api/",{add_user_api_access: nombre}).done(function(info)
    {
        if(info)
            alert("Usuario añadido");
        else
            alert("Problema a la hora de insertar el Usuario");
        Plugin();
    });
}
function quitarUsuario()
{
    var nombre = $("#accesslist").val();
    $.post("api/",{remove_user_api_access: nombre}).done(function(info)
    {
        if(info)
            alert("Usuario eliminado");
        else
            alert("Problema a la hora de eliminar el Usuario");
        Plugin();
    });
}
function Plugin()
    {
        var objeto_pl = JSON.parse($(".infoPlugin").html());
        var cont = "<div class='plugins'><h3>Plugins/API</h3><button class='btn doc api'>Documentación API</button><button class='btn doc plugin'>Documentación Plugin</button><button class='btn doc JSON'>Documentación Objetos JSON</button><h4>Plugins</h4>";
        for(var plugin of objeto_pl)
        {
            cont += "<div class='acc_plugin'><span class='titulo'>"+plugin.name+"</span><p class='parr'><b>Descripción:</b> "+plugin.description+"</p><p class='parr'><b>Autor:</b> "+plugin.author+"</p><p class='parr'><b>Imagen:</b><br /><img src='"+plugin.image+"' style='max-width:150px;border-radius:5px;border:solid 1px darkgrey' /></p><p class='parr'><b>URL:</b> <a target='_blank' href='"+plugin.url+"'>"+plugin.url+"</a></p></div>";
        }
        cont += "<h4>API</h4><div class='api_row'><span class='us'>Usuario Existentes:</span><select id='userlist'></select><button id='access_user'>Añadir Acceso</button></div><div class='api_row'><span class='us'>Usuarios con acceso a la API:</span><select id='accesslist'></select><button id='delete_user'>Quitar Acceso</button></div>";
        $(".content").html(cont);

        $("#access_user").click(anadirUsuario);
        $("#delete_user").click(quitarUsuario);
        
        $.get("api/?users").done(function(data){
            var sel = $("#userlist");
            for(var user of JSON.parse(data))
            {
                var html = sel.html();
                sel.html(html+"<option>"+user.nombre+"</option>");
            }
        });
        $.get("api/?access_user_api").done(function(data){
            var sel = $("#accesslist");
            var obj = JSON.parse(data);
            for(var us of obj.usuario)
            {
                var html = sel.html();
                sel.html(html+"<option value=',"+JSON.stringify(us)+"'>"+us.nombre+"</option>");
            }
        });
    }