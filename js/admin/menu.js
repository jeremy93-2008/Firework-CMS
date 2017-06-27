/**
 * Guarda la información modificada en menu.json
 */
function Guardar()
{
    var json = pCrearJSONMenu();
    $.post("api/", { set_menu_json: json }).done(function(info)
        {
            toastInfo(info);
        });
}
function Eliminar()
{
    if($(".accordion").length > 1)
        $(this).parent().parent().remove();
    else
        toastInfo("No puedes dejar la web sin menú");
}
function Annadir()
{
    var $acc = $($(".accordion")[$(".accordion").length-1]);
    $acc_new = $acc.clone(true,true);
    $acc_new.attr("id","acc"+(parseInt($acc.attr("arr"))+1));
    $acc_new.attr("arr",parseInt($acc.attr("arr"))+1);
    var $add = $acc_new.find("select");
    $acc.after($acc_new);
    $add.prop("disabled",false);
}
function cambiaOrden()
{
    var $acc = $(this).parent().parent().parent();
    $acc.attr("orden",$(this).val());
}
function cambiaTitulo()
{
    var $acc = $(this).parent().parent().parent();
    $acc.attr("titulo",$(this).val());
    $acc.children()[0].innerHTML = $(this).val();    
}
function cambiaEnlace()
{
    var txt = $(this).val();
    if(txt == "nuevo")
    {
        var bool = true
        do
        {
            txt = encodeURIComponent(prompt("Escriba su enlace personalizado:","http://"));
            if(txt != "")
                bool = false;
        }while(bool)
    }
    var $acc = $(this).parent().parent().parent();
    $acc.attr("enlace",txt);     
}
/**
 * Construye el JSON cuando se va a guardar en el fichero
 */
function pCrearJSONMenu()
{
    $json="[";
    for(var $eachMenu of $(".accordion"))
    {
        $json += "{\"titulo\":\""+$eachMenu.getAttribute("titulo")+"\",\"orden\":\""+$eachMenu.getAttribute("orden")+"\",\"url\":\""+decodeURIComponent($eachMenu.getAttribute("enlace"))+"\"},";
    }
    $json = $json.substring(0,$json.length-1);
    $json += "]";
    return $json;
}