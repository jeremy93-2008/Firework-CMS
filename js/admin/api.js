function anadirUsuario()
{
    var nombre = $("#userlist").val();
    $.post("api/",{add_user_api_access: nombre}).done(function(info)
    {
        if(info)
            toastInfo("Usuario añadido");
        else
            toastInfo("Problema a la hora de insertar el Usuario");
        Plugin();
    });
}
function quitarUsuario()
{
    var nombre = $("#accesslist").val();
    $.post("api/",{remove_user_api_access: nombre}).done(function(info)
    {
        if(info)
            toastInfo("Usuario eliminado");
        else
            toastInfo("Problema a la hora de eliminar el Usuario");
        Plugin();
    });
}
/**
 * Subir el ZIP al servidor y descomprimirlo y instalar el plugin
 */
function UploadPlugin()
{
    var form = new FormData();
    form.append("opcionZip", "plugin");
    form.append("set_zip_file", "Zip");
    form.append("zipFile", $("#uploadPlugin").prop("files")[0]);
    $.ajax({
        url: "api/", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData: false,        // To send DOMDocument or non processed data file it is set to false
        success: function (data)   // A function to be called if request succeeds
        {
            toastInfo("Archivo súbido con éxito");
            Plugin();
        }
    });
}
/**
 * Activa o Desactiva un Plugin según su estado actual
 */
function ActivePlugin()
{
    let color = $(this).css("color");
    let txt = "¿Desea activar este plugin: "+$(this).attr("plugin")+" ?";
    if(color=="rgb(0, 128, 0)")
        txt = "¿Desea desactivar este plugin: "+$(this).attr("plugin")+" ?";
    let conf = confirm(txt);
    
    if(conf)
    {
        $.post("api/",{set_plugin_activation: $(this).attr("plugin")}).done(function(info)
        {
            if(info!="false")
                toastInfo("Plugin activado con éxito")
            else
                toastInfo("Plugin desactivado con éxito")
            $.get("api/?plugin_info").done(function(data)
            {
                $(".infoPlugin").html(data);
                $(".btn.side.plugin").remove();
                var a = 0;
                let html = "";
                for(pl of JSON.parse(data))
                {
                    if(pl.instance != undefined)
                    {
                        html += "<button idPlugin="+a+" class='btn side plugin'>"+pl.name+"</button>"
                    }
                    a++;
                }
                $(".infoPlugin").after(html);
                Plugin();
                $(".btn.side.plugin").click(Clique);
            });
        });
    }
}
/**
 * Desinstala un Plugin elegido
 */
function UninstallPlugin()
{
    let txt = "¿Está seguro de querer desinstalar este plugin?";
    let conf = confirm(txt);
    
    if(conf)
    {
        $.post("api/",{uninstall_plugin: $(this).attr("plugin")}).done(function(info)
        {
            console.log(info);
            if(info!="false")
                toastInfo("Plugin desinstalado con éxito")
            else
                toastInfo("No se pudo desinstalar el plugin")
            $.get("api/?plugin_info").done(function(data)
            {
                $(".infoPlugin").html(data);
                $(".btn.side.plugin").remove();
                var a = 0;
                let html = "";
                for(pl of JSON.parse(data))
                {
                    if(pl.instance != undefined)
                    {
                        html += "<button idPlugin="+a+" class='btn side plugin'>"+pl.name+"</button>"
                    }
                    a++;
                }
                $(".infoPlugin").after(html);
                Plugin();
                $(".btn.side.plugin").click(Clique);
            });
        });
    }
}