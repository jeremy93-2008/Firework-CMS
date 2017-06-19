$(function()
{
    window.onscroll = function(){CambiarHeader();};
    window.onload = function(){CambiarHeader();};
    $(".learn").click(Bajar);
    function CambiarHeader()
    {
        if(window.scrollY >= 80)
        {
            $(".header").addClass("fijado");
            $(".flecha i").css("opacity","0");
            $(".big").css("display","none");
            $(".small").css("display","inline-block");
        }else
        {
            $(".header").removeClass("fijado");
            $(".flecha i").css("opacity","1");
            $(".big").css("display","inline-block");
            $(".small").css("display","none");
        }
    }
    function Bajar()
    {
        CambiarHeader();
    }
});