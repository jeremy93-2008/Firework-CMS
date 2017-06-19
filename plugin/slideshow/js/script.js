$(function()
{
    $(".file-image ul li").click(PasarALista);
    $(".slide-image ul li").click(PasarAImagen);
    $(".guardarSlideshow").click(guardarLista);
    function PasarALista()
    {
        var fuente = $(this).clone();
        $(".slide-image ul").append(fuente);
        $(".slide-image ul li").click(PasarAImagen);
    }
    function PasarAImagen()
    {
        var fuente = $(this);
        fuente.remove();       
    }
    function guardarLista()
    {
        var lista = "";
        var tiempo = $("#timer").val();
        var slide = $(".slide-image")[0];
        console.log(slide);
        var elementos = $(slide).find("li");   
        for(var elem of elementos)
        {
            lista += $(elem).children("img").attr("src")+",";
        }
        lista = lista.substring(0,lista.length-1);
        $.post("#",{pluginName:'Slideshow Plugin',datos:lista,intervalo:tiempo}).done(function(info)
        {
           alert($("<html/>").html(info).find("#returnPlugin").text());
        });
    }
});