$(function()
{
    if($(".SlideshowMain").length > 0)
        Diapositiva();

    function Diapositiva()
    {
        var tiempo = $(".SlideshowMain").attr("timer");
        var intervalo = window.setInterval(function()
        {
            var diapositivaActual = $(".SlideshowMain .current");
            var diapositivaSiguiente = $(".SlideshowMain .current").next();
            diapositivaActual.removeClass("current");
            if(diapositivaSiguiente.length < 1)
            {
                diapositivaSiguiente = $(".SlideshowMain div:first");
                $(".SlideshowMain div").css("display","none");
            }
            var int = parseInt(tiempo)/4;
            diapositivaSiguiente.addClass("current");
            diapositivaSiguiente.fadeIn(int);
        },parseInt(tiempo));
    }
});