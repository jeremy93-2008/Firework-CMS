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