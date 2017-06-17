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
        var elementos = $(".slide-image ul li");
        console.log($(".slide-image"));   
        for(var elem of elementos)
        {
            lista += $(elem).children("img").attr("src")+",";
        }
        lista = lista.substring(0,lista.length-1);
        $.post("#",{pluginName:'Slideshow Plugin',datos:lista,intervalo:tiempo}).done(function(info)
        {
           var doc = document.createElement("div");
           doc.innerHTML = info;
           alert(doc.querySelector("#returnPlugin").innerHTML);
        });
    }
});