 function verDetalle()
{
    // Hack: el $(this) no funciona aqui, ni idea del porque.
    var txt = this.getAttribute("alt").split("\n");
    txt[0] = "<strong>"+txt[0]+"</strong>";
    var fn = txt.join("<br />");
    $(".info").html("<h3 style='margin-top: 0px;margin-bottom: 10px;'>Detalle</h3>"+fn);
}