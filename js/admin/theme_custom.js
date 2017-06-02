/**
 * Genera la vista para la personalización de los temas en Firework CMS
 */
function verPersonalizacion()
{
    $.get("api/?getCSSModel").done(function (data) {
        
            $(".content").html("");
            var txt = "<div class='theme_custom'><h2 style='margin-top: 0px;padding-top: 5px;'>Personalizar <sub style='font-size:12px'>la web</sub></h2>";
            var obj = JSON.parse(data);
            for(var clase of obj.clases)
            {
                var cadena_clase = clase.split(",");
                txt += "<div class='custom_tem' clase_custom='"+cadena_clase[0]+"'><h3 class='name'>"+cadena_clase[1]+"</h3>";
                for(var prop of obj.propiedades)
                {
                    var valor = "";
                    switch(prop.type)
                    {
                        case "color": valor = "<input class='custom_val' type='color' value='"+prop.value+"' />";break;
                        case "text": valor = "<input class='custom_val' type='text' value='"+prop.value+"' />";break;
                        case "numeric": var tabla_opcion = prop.value.split(",");
                                        if(tabla_opcion.length>1)
                                        {
                                            for(var inp of tabla_opcion)
                                            {
                                                valor += "<input class='custom_val' type='number' value='"+inp+"'/>";
                                            }
                                        }else
                                        {
                                            valor = "<input class='custom_val' type='number' value='"+prop.value+"'/>";
                                        };break;
                        case "select": var tabla_opcion = prop.value.split(",");
                                        valor = "<select class='custom_val'>";
                                        for(var linea of tabla_opcion)
                                        {
                                            valor += "<option>"+linea+"</option>";
                                        }
                                        valor += "</select>";break;
                        default: valor = "<input class='custom_val' type='text' value='"+prop.value+"' />";break;
                    }
                    txt += "<div class='custom_prop'><p>"+prop.name+"</p><span>"+prop.property+"</span>"+valor+"</div>";
                }
                txt += "</div>";
                $(".content").html(txt);
                document.body.style.cursor = "default";
            }
        });
}