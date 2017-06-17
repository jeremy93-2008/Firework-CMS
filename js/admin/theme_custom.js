/**
 * Genera la vista para la personalización de los temas en Firework CMS
 */
function verPersonalizacion()
{
    $.get("api/?getCSSModel").done(function (data) {
        
            $(".content").html("");
            var txt = "<div class='theme_custom'><h2 style='margin-top: 0px;padding-top: 5px;'>Personalizar <sub style='font-size:12px'>la web</sub></h2><h5>Activar Personalización de temas: <select id='enabled_custom'><option value='true'>Si</option><option value='false'>No</option></select></h5>";
            var obj = JSON.parse(data);
            for(var clase of obj.clases)
            {
                var cadena_clase = clase.split(",");
                
                txt += "<div class='custom_tem' clase_custom='"+cadena_clase[0]+"'><h3 title='"+cadena_clase[0]+"' class='name'><i class='fa fa-caret-right cursor' aria-hidden='true'></i>"+cadena_clase[1]+"<button title='Revertir cambios de "+cadena_clase[1]+" ("+cadena_clase[0]+")' class='btn revertir_btn'>Revertir cambios</button></h3><div class='content_prop'>";
                for(var prop of obj.propiedades)
                {
                    var valor = "";
                    switch(prop.type)
                    {
                        case "color": valor = "<input class='custom_val' type='color' default='"+prop.value+"' value='"+prop.value+"' />";break;
                        case "text": valor = "<input class='custom_val' type='text' default='"+prop.value+"' value='"+prop.value+"' />";break;
                        case "numeric": var tabla_opcion = prop.value.split(",");
                                        if(tabla_opcion.length>1)
                                        {
                                            for(var inp of tabla_opcion)
                                            {
                                                valor += "<span class='val'><input class='custom_val' type='number' unit='px' default='"+prop.value+"' value='"+inp+"'/> px</span>";
                                            }
                                        }else
                                        {
                                            valor = "<span class='val'><input class='custom_val' type='number' unit='px' default='"+prop.value+"' value='"+prop.value+"'/> px</span>";
                                        };break;
                        case "select": var tabla_opcion = prop.value.split(",");
                                        valor = "<select default='"+tabla_opcion[0]+"' class='custom_val'>";
                                        for(var linea of tabla_opcion)
                                        {
                                            valor += "<option>"+linea+"</option>";
                                        }
                                        valor += "</select>";break;
                        default: valor = "<input class='custom_val' default='"+prop.value+"' type='text' value='"+prop.value+"' />";break;
                    }
                    txt += "<div class='custom_prop' prop='"+prop.property+"'><p>"+prop.name+"</p><span>"+prop.property+"</span>"+valor+"</div>";
                }
                txt += "</div></div>";
            }
            txt += "<button class='btn custom_save'>Guardar la personalización</button>";
            $(".content").html(txt);
            document.body.style.cursor = "default";
            $(".custom_tem .name").click(AbriryCerrar);
            $("#enabled_custom").on("change",HabilitarPersonalizacion);
            $(".custom_save").click(GuardarPersonalizacion);
            $(".revertir_btn").click(VolveraValoresporDefecto);
            $.get("api/?getCSSSave").done(function(data)
                {
                    var obj = JSON.parse(data);
                    var activado = obj.activado;
                    if(activado!="true")
                    {
                        $("#enabled_custom option[value='false']").attr("selected","selected");
                    }
                    for(var each of obj.propiedades)
                    {
                        var clase = $(".custom_tem[clase_custom='"+each.class+"']");
                        var propiedad = clase.find(".custom_prop[prop='"+each.property+"']");
                        var valores = propiedad.find(".custom_val");
                        var asignar = null;
                        if(each.value.indexOf(",") != -1)
                        {
                            asignar = each.value.split(",");
                        }
                        if(valores.length>1)
                        {
                            var ind = 0;
                            for(var vale of valores)
                            {
                                if(asignar != null)
                                {
                                    $(vale).val(asignar[ind]);
                                }else
                                {
                                    $(vale).val(each.value);
                                }
                                ind++;
                            }
                        }else
                        {
                            valores.val(each.value);
                        }
                    }
                });
        });
}
/**
 * Permite abrir y cerrar la pestaña correspondiente con la personalizacion css de un custom_tem en concreto
 */
function AbriryCerrar()
{
    $(this).parent().children(".content_prop").toggle(400);
    $(this).children(".cursor").toggleClass("fa-caret-right");
    $(this).children(".cursor").toggleClass("fa-caret-down");
}
/**
 * Permite habilitar o no la opción de personalización de temas
 */
function HabilitarPersonalizacion()
{
    var eleccion_valor = $("#enabled_custom").val();
    $.post("api/",{set_theme_custom_enabled:eleccion_valor}).done(function(info)
    {
        if(info)
        {
            alert("Se ha cambiado los parametros requeridos del personalizador de temas.");
        }
    });
}
/**
 * Permite guardar y generar el CSS para tener la personalizacion de los temas
 */
function GuardarPersonalizacion()
{
    var obj_final = "[";
    var lista_temas = $(".custom_tem");
    for(var obj of lista_temas)
    {
        var propiedades = $(obj).find(".custom_prop");
        for(var each_prop of propiedades)
        {
            var valor = "";
            var valores = $(each_prop).find(".custom_val");
            if(valores.length>1)
            {
                for(var vale of valores)
                {
                    valor += $(vale).val() + ","
                }
                valor = valor.substring(0,valor.length-1);
            }else
            {
                valor = $(each_prop).find(".custom_val").val().toUpperCase()
            }
            var defecto = $(each_prop).find(".custom_val").attr("default").toUpperCase();
            if(valor != defecto)
                obj_final += "{\"class\":\""+$(obj).attr("clase_custom")+"\",\"property\":\""+$(each_prop).attr("prop")+"\",\"value\":\""+valor+"\"},";
        }
    }
    if(obj_final.length>1)
        obj_final = obj_final.substring(0,obj_final.length-1);
    obj_final += "]";
    var string = obj_final;
    $.post("api/",{set_css_and_generate:string}).done(function(info)
    {
        if(info)
        {
            alert("Se han guardado los datos cambiados");
        }
    });
}
/**
 * Permitir insertar los valores por defecto en una clase en particular, y asi exigir su comportamiento por defecto a nivel de plantilla
 */
function VolveraValoresporDefecto()
{
    var lista_prop = $(this).parent().parent().find(".custom_prop");
    for(var prop of lista_prop)
    {
        var valores = lista_prop.find(".custom_val")
        for(var valu of valores)
        {
            if(valu.tagName == "SELECT")
            {
                var opcion = $(valu).children("option:first");
                opcion.attr("selected","selected");
            }else
            {
                var defecto = $(valu).attr("default");
                if(defecto.indexOf(",") != -1)
                    defecto = defecto.split(",")[0];
                $(valu).val(defecto);
            }
        }
    }
}