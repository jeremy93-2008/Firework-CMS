/**
 *  Establece este tema como predeterminado 
 */
function SetMain()
{
    var txt = $(this).parent().parent().attr("ruta")
    $.post("api/",{set_main_theme:txt}).done(function(info)
    {
        if(info)
            toastInfo("Su tema principal se ha cambiado con éxito");
            refreshMainTheme();
    });
}
/**
 *  Refresca la vista de Temas para ver el nuevo tema como principal 
 */
function refreshMainTheme()
{
    $.get("api/?current_theme_more").done(function(data)
    {
        var obj = JSON.parse(data);
        var identificador = "#"+espacios(obj.nombre+obj.descripcion);
        $(".selectedTheme").removeClass("selectedTheme");
        $(identificador).addClass("selectedTheme");
    });
}
/*
* Convierte los espacios de una cadena en valores contiguos 
*/
function espacios(str)
{
    return str.replace(/\s/g,"");
}
/**
 * Abre la ventana de personalización, que te permite crear tus propios estilos sin necesidad de un tema predefinido. 
 */
function SetCustom()
{
    document.body.style.cursor = "wait";
    verPersonalizacion();
}
/**
 * Subir el ZIP al servidor y descomprimirlo y instalar la plantilla
 */
function UploadTheme()
{
    var form = new FormData();
    form.append("opcionZip", "template");
    form.append("set_zip_file", "Zip");
    form.append("zipFile", $("#uploadTema").prop("files")[0]);
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
            Tema();
        }
    });
}