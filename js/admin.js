$(function () {
    $(".side").click(Clique);
    $("#cerrar").click(Cerrar);
    function Cerrar() {
        $.get("api/?close").done(function (data) {
            alert("Sesión cerrada");
            location.href = ".";
        });
    }
    /**
     * Gestiona con los Switch el cambio de color de los botones de la barra cuando se les selecciona y con switch redirreciona cada botón en su función correspondiente
     */
    function Clique() {
        $(".side").removeClass("selecc");
        $(this).addClass("selecc");
        var valor = $(this).html();
        switch (valor) {
            case "Estadistica": Estadistica(); break;
            case "Articulos": Articulos(); break;
            case "Páginas": Paginas(); break;
            case "Medios": Medios(); break;
            case "Comentarios":Comentar();break;
            case "Menú": Menu(); break;
            case "Temas": Tema();break;
            case "Usuarios": Usuarios(); break;
            case "Plugins/API":Plugin();break;
            case "Configuración":Config();break;
            default:
                var pl = $(this).attr("idplugin");
                var t = false;
                if (pl != undefined) {
                    for (var a = 0; a < $(".admin").length; a++) {
                        if ($($(".admin")[a]).attr("idplugin") == pl) {
                            $(".content").html($($(".admin")[a]).html());
                            t = true;
                        }
                    }
                } else {
                    t = true;
                }
                if (!t)
                    alert("Este plugin no tiene panel de administración");
                break;
        }
    }
    Estadistica();
    /**
     * Genera la vista para mostrar el JSON de las estadisticas
     */
    function Estadistica() {
        $.get("api/?statistic").done(function (data) {
            if (data.indexOf("Necesitas registrarte como usuario") == -1) {
                $(".content").html("");
                var txt = "<div class='stats'><h2 style='margin-top: 0px;padding-top: 5px;'>Estadisticas <sub style='font-size:12px'>De esta semana</sub></h2>";
                //Estadistica
                var datos = JSON.parse(data);

                var curr = new Date;
                var first = curr.getDate() - curr.getDay() + 1;
                var max_numero = 0;
                var porcentaje = 10;
                for (var a = 0; a < 7; a++) {
                    var tabla = [];
                    txt += "<div class='colstats'>";
                    var fecha = new Date(new Date().setDate(first + a));
                    var fecha_compatible = CeroIzq(fecha.getDate()) + "/" + CeroIzq(fecha.getMonth() + 1) + "/" + fecha.getFullYear();
                    var cant = 0;
                    for (var linea of datos) {
                        if (fecha_compatible == linea.fecha) {
                            cant++;
                            tabla.push(linea.url);
                        }
                    }
                    var accesotxt = VerAcceso(tabla);
                    if (max_numero < cant)
                        max_numero = cant;
                    if (max_numero > 29)
                        porcentaje = 5;
                    else if (max_numero > 90)
                        porcentaje = 2;
                    txt += "<div title='Visitas: " + cant + "' alt='Visitas: " + cant + accesotxt + "' style='height:" + (cant * porcentaje) + "px' class='chart'></div>";
                    txt += "<p class='title_chart'>" + fecha_compatible + "</p>";
                    txt += "</div>";
                }
                txt += "<div class='info'></div></div>";
                $(".content").html(txt);
                $(".chart").click(verDetalle);
            }
        });
    }
    /**
     * Genera vista para visualizar la lista de articulos desde el array de json enviado.
     */
    function Articulos() {
        $.get("api/?Allarticle").done(function (data) {
            $(".content").html("");
            var txt = "<div class='articles'><h2 style='margin-top: 0px;padding-top: 5px;'>Articulos <sub style='font-size:12px'>De la Web</sub></h2><button class='add_article'>Nuevo Articulo</button>";
            //Articulo
            var datos = JSON.parse(data);
            var n_veces = 1;
            for (var linea of datos) {
                var js = JSON.stringify(linea);
                var img = "";
                if (linea.image != "")
                    img = "<img height='150px' src='" + linea.image + "' />";
                else
                    img = "<img height='150px' src='https://placeholdit.imgix.net/~text?txtsize=33&txt=Imagen&w=150&h=150' />";
                var description = linea.description;
                if (linea.description.length > 34)
                    linea.description = linea.description.substring(0, 36) + "...";
                if (n_veces % 2 == 1 && n_veces != 1) {
                    txt += "<br><div title='Eliminar Articulo' articulo='" + linea.id + "' class='close-page'><i class='fa fa-times' aria-hidden='true' /></div><div alt='" + js + "' class='single'><h3>" + linea.name + "</h3>" + img + "<p title='" + description + "' class='desc'>" + linea.description + "</p><p class='cat'>" + linea.category + "</p><p class='date'>" + linea.date + "</p><p class='author'>" + linea.autor + "</p></div>";
                } else {
                    txt += "<div title='Eliminar Articulo' articulo='" + linea.id + "' class='close-page'><i class='fa fa-times' aria-hidden='true' /></div><div alt='" + js + "' class='single'><h3>" + linea.name + "</h3>" + img + "<p title='" + description + "' class='desc'>" + linea.description + "</p><p class='cat'>" + linea.category + "</p><p class='date'>" + linea.date + "</p><p class='author'>" + linea.autor + "</p></div>";
                }

                n_veces++;
            }
            txt += "<div class='info'></div></div>";
            $(".content").html(txt);
            $(".single").click(EditarArtIndiv);
            $(".add_article").click(NuevoArtIndiv);
            $(".close-page").click(EliminarArt);
        });
    }
    /**
    * Genera vista para visualizar la lista de paginas desde el array de json enviado.
    */
    function Paginas() {
        $.get("api/?Allpage").done(function (data) {
            $(".content").html("");
            var txt = "<div class='articles'><h2 style='margin-top: 0px;padding-top: 5px;'>Página <sub style='font-size:12px'>De la web</sub></h2><button class='add_page'>Nueva Página</button>";
            //Página
            var datos = JSON.parse(data);
            var n_veces = 1;
            for (var linea of datos) {
                var js = JSON.stringify(linea);
                var img = "";
                if (linea.image != "")
                    img = "<img height='150px' src='" + linea.image + "' />";
                else
                    img = "<img height='150px' src='https://placeholdit.imgix.net/~text?txtsize=33&txt=Imagen&w=150&h=150' />";
                var description = linea.description;
                if (linea.description.length > 34)
                    linea.description = linea.description.substring(0, 36) + "...";
                if (n_veces % 2 == 1 && n_veces != 1) {
                    txt += "<br><div title='Eliminar Página' pagina='" + linea.id + "' class='close-page'><i class='fa fa-times' aria-hidden='true' /></div><div alt='" + js + "' class='single'><h3>" + linea.name + "</h3>" + img + "<p title='" + description + "' class='desc'>" + linea.description + "</p><p class='author'>" + linea.autor + "</p></div>";
                } else {
                    txt += "<div title='Eliminar Página' pagina='" + linea.id + "' class='close-page'><i class='fa fa-times' aria-hidden='true' /></div><div alt='" + js + "' class='single'><h3>" + linea.name + "</h3>" + img + "<p title='" + description + "' class='desc'>" + linea.description + "</p><p class='author'>" + linea.autor + "</p></div>";
                }
                n_veces++;
            }
            txt += "<div class='info'></div></div>";
            $(".content").html(txt);
            $(".single").click(EditarPageIndiv);
            $(".add_page").click(NuevoPageIndiv);
            $(".close-page").click(EliminarPag);
        });
    }
    /**
     * Genera una vista  para visualizar la lista de imagenes desde el array de json enviado
     * 
     */
    function Medios() {
        var contenedor = createEmmetNodes(".uploadimg.medios>.header>h4.title{Im&aacutegenes}^.contenido>h4{Tus im&aacutegenes}+.imagen+p{-o-}+h4{Sube una imagen}+.subir>input[type=file,id=archivo]+i.fa.fa-upload.sube[aria-hidden=true,name=set_image]");
        $(".content").html("");
        $(".content").append(contenedor);
        $(".sube").click(subirImagenes);
        $.get("api/?getImage").done(function (data) {
            $(".imagen").html("");
            for (var img of JSON.parse(data)) {
                var antiguo = $(".imagen").html();
                $(".imagen").html(antiguo + "<img src='img/client/" + img + "' style='width:128px;margin: 10px;' />");
                if (titulo)
                    $(".imagen img").click(VerImg);
                else
                    $(".imagen img").click(VerImg);
            }
        });
    }
     /**
     * Genera la Vista para los comentarios
     */
    function Comentar()
    {
         $.get("api/?Allarticle").done(function (data) {
             var cont = "<div class='comment'><h3>Comentarios</h3><div class='moderation'>Activar la Moderación: <select id='mod_comment'><option value='true'>Sí</option><option value='false'>No</option></select></div><br />";
             for(var art of JSON.parse(data))
             {
                cont += "<div class='accordion' id='"+art.id+"'><span class='titulo'>"+art.name+"</span><p class='parr'><span>Descripción: </span>"+art.description+"</p><p class='parr'><span>Fecha: </span>"+art.date+"</p><div class='accordion'><h4>Comentarios a Moderar:</h4><div class='commentmodo"+art.id+"'></div></div> <div class='accordion'><h4>Comentarios Visibles:</h4><div class='comment"+art.id+"'></div></div></div>";
             }
             $(".content").html(cont);
             $.get("api/?getComments").done(function (data)
             {
                var obj_t = JSON.parse(data);
                // Comentarios moderados
                for(var a = 0;a < obj_t[1].length;a++)
                {
                    //tipocomentario/articulo/n_com
                    var num =  obj_t[1][a].length+1;
                    var bool = true;
                    for(var b = 0;b < num;b++)
                    {
                        var html = $(".commentmodo"+(a+1)).html();
                        var code = obj_t[1][a][b] || "";
                        var txt = code;
                        if(code != "")
                        {
                            txt = "<div idarticle='"+(a+1)+"' idcomment='"+b+"' class='comment_container'><button title='Aprobar Comentario' class='revisar'><i class='fa fa-check' aria-hidden=\"true\"></i></button><button title='Eliminar Comentario' class='eliminar_mod'><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></button><br /><br /><div class='com'>"+code+"</div></div>";
                            bool = false;
                        }
                        $(".commentmodo"+(a+1)).html(html+txt)
                    }
                    var html = $(".commentmodo"+(a+1)).html();
                    if(bool)
                        $(".commentmodo"+(a+1)).html(html+"<p class='nothing'>Ningún comentario que moderar.</p>")
                }
                // Comentarios normales
                for(var a = 0;a < obj_t[0].length;a++)
                {
                    //tipocomentario/articulo/n_com
                    var num =  obj_t[0][a].length+1;
                    var bool = true;
                    for(var b = 0;b < num;b++)
                    {
                        var html = $(".comment"+(a+1)).html();
                        var code = obj_t[0][a][b] || "";
                        var txt = code;
                        if(code != ""){
                            txt = "<div idarticle='"+(a+1)+"' idcomment='"+b+"' class='comment_container'><button title='Eliminar Comentario'class='eliminar'><i class=\"fa fa-trash\" aria-hidden=\"true\" /></button><br /><div class='com'>"+code+"</div></div>";
                            bool = false;
                        }
                        $(".comment"+(a+1)).html(html+txt);
                    }
                    var html = $(".comment"+(a+1)).html();
                    if(bool)
                        $(".comment"+(a+1)).html(html+"<p class='nothing'>Ningún comentario para este articulo.</p>")
                }
                $("#mod_comment").on("change",function()
                {
                    $.post("api/",{set_moderation:$("#mod_comment").val()}).done(function(info){
                        if(info)
                        {
                            alert("Configuración de moderación cambiada");
                        }
                    });
                });
                $.get("api/?isModerated").done(function(data) 
                {
                    $("#mod_comment option[value="+data+"]").attr("selected","selected");
                });
                $(".revisar").click(ApproveComment);
                $(".eliminar").click(EliminarComment);
                $(".eliminar_mod").click(EliminarCommentMod);
             });
         });
    }
    /**
     * Genera la vista para gestionar los temas implementado y que se puede implementar en el CMS. 
     */
    function Tema()
    {
        $.get("api/?getThemes").done(function (data) {
            var contenedor = "<div class='temas'><h3>Temas</h3><button class='btn custom_theme' style='margin-bottom:10px'>Personalizar Estilos</button>";
            for(var tema of JSON.parse(data))
            {
                contenedor += "<div ruta='"+tema.ruta+"' id='"+espacios(tema.nombre+tema.descripcion)+"' class='accordion'>"+tema.nombre+"<p class='parr' style='font-style:italic;margin-left:0px'><span>Descripción: </span>"+tema.descripcion+"</p><p class='parr'><span>Imagen:</span><img src='"+tema.imagen+"' style='width:128px;border: solid 1px darkgrey;margin: 0px 5px;vertical-align: middle;' /></p><p class='parr' style='font-style:italic;margin-left:0px'><span>Autor: </span>"+tema.autor+"</p><p class='parr' style='font-style:italic;margin-left:0px'><span>URL: </span><a target='_blank' href='"+tema.url+"'>"+tema.url+"</a></p><p class='parr btn_cont'><button class='btn set_main'>Escoger como Tema Principal</button></p></div>";
            }
            contenedor += "</div>";
            $(".content").html(contenedor);
            $.get("api/?current_theme_more").done(function(data)
            {
                var obj = JSON.parse(data);
                var identificador = "#"+espacios(obj.nombre+obj.descripcion);
                $(".selectedTheme").removeClass("selectedTheme");
                $(identificador).addClass("selectedTheme");
            });
            console.log($(".set_main"));
            console.log($(".custom_theme"));
            $(".set_main").click(SetMain);
            $(".custom_theme").click(SetCustom);
        });
               
    }
    /**
     * Genera la vista para gestionar el menú implementado en el CMS 
     */
    function Menu() {
        $.get("api/?getMenuFull").done(function (data) {
            var cadena_menu = "";
            var a = 0;
            var obj_full = JSON.parse(data);
            for (var menu of obj_full[0]) {
                cadena_menu += "+(div.accordion#acc" + a + "[arr=" + a + ",titulo=" + menu.titulo + ",orden=" + menu.orden + ",enlace=" + encodeURIComponent(menu.url) + "]>span.titulo{" + menu.titulo + "}+div.icon>i.fa.fa-times.delete[aria-hidden=true,arr=" + a + ",title=Eliminar menu]^div.acc_submenu>div.title_menu>label{Etiqueta: }+input.titulo[type=text,value=" + menu.titulo + "]^div.orden_menu>label{Orden: }+input.orden[type=number,value=" + menu.orden + "]^div.url_menu>label{Enlace a: }+select.enlace[bind=acc" + a + "])";
                a++;
            }
            var contenedor = createEmmetNodes("div.menu>h3{Men&uacute}+button.annadir_menu{Añadir nuevo men&uacute}" + cadena_menu);
            $(".content").html("");
            $(".content").append(contenedor);
            for (var selecto of $(".accordion .enlace")) {
                var seleccionado = false;
                var $selecto = $(selecto);
                var $cont = $("#" + selecto.getAttribute("bind"))
                $selecto.append("<optgroup label='Articulo'>");
                for (var linea of obj_full[1]) {
                    var url = encodeURIComponent("?pa=1&ac=" + linea.id);
                    if ($cont.attr("enlace") != url) {
                        $selecto.append("<option value='" + url + "'>" + linea.name + "</option>");
                    }
                    else {
                        $selecto.append("<option selected value='" + url + "'>" + linea.name + "</option>");
                        seleccionado = true;
                    }
                }
                var bool = false;
                $selecto.append("<optgroup label='Páginas'>");
                for (var linea of obj_full[2]) {
                    var url = encodeURIComponent("?pa=" + linea.id);
                    if ($cont.attr("enlace") != url) {
                        $selecto.append("<option value='" + url + "'>" + linea.name + "</option>");
                    } else if (url == encodeURIComponent("?pa=1") && bool == false) {
                        $selecto.attr('disabled', 'disabled');
                        $selecto.append("<option selected value='" + url + "'>" + linea.name + "</option>");
                        bool = true;
                        seleccionado = true;
                    }
                    else {
                        $selecto.append("<option selected value='" + url + "'>" + linea.name + "</option>");
                        seleccionado = true;
                    }
                }
                $selecto.append("<optgroup label='Otros'>");
                if (seleccionado)
                    $selecto.append("<option value='nuevo'>Enlace personalizado...</option>");
                else
                    $selecto.append("<option selected value='nuevo'>Enlace personalizado...</option>");
            }
            $(".menu").append($("<button class='guardar_menu'>Guardar Men&uacute</button>"))
            $(".menu .annadir_menu").click(Annadir)
            $(".menu .guardar_menu").click(Guardar)
            $(".accordion .delete").click(Eliminar)
            $(".accordion .titulo").on("input", cambiaTitulo)
            $(".accordion .orden").on("change", cambiaOrden)
            $(".accordion .enlace").on("change", cambiaEnlace)
        });
    }
    /**
     * Genera la vista para gestionar el CRUD de usuarios
     */
    function Usuarios()
    {
        $.get("api/?users").done(function(data)
        {
            var cont = "<div class='users'><h3>Usuarios</h3><button class='add_user'>Añadir Usuario</button>";
            for(var usuario of JSON.parse(data))
            {
                cont += "<div json='"+JSON.stringify(usuario)+"' class='accordion'><span class='titulo'>"+usuario.nombre+"</span><div class='icon' title='Eliminar Página'><i class='fa fa-times delete' aria-hidden='true' /></div><p class='parr'><b>Contraseña:</b> "+usuario.contrasenia+"</p><p class='parr'><b>Rol:</b> "+Rol(usuario.rol)+"</p></div>";
            }
            cont += "</div><div class='usuario'><h4>Crear nuevo Usuario</h4><p><label>Nombre de Usuario: </label><input class='username' type='text' /></p><p><label>Contraseña: </label><input class='pass' type='password' /></p><p><label>Rol: </label><select id='rol'><option value='1'>Usuario</option><option value='2'>Colaborador</option><option value='3'>Editor</option><option value='4'>Administrador</option></select></p><button class='add_usuario'>Crear Usuario</button></div>";
            $(".content").html(cont)
            $(".accordion .icon").click(EliminarUser);
            $(".users .add_user").click(MostrarUser);
            $(".usuario .add_usuario").click(CrearUser);
        });
    }
    /**
     * Genera la Vista para Plugins y APIs
     */
    function Plugin()
    {
        var objeto_pl = JSON.parse($(".infoPlugin").html());
        var cont = "<div class='plugins'><h3>Plugins/API</h3><a target='_blank' href='../doc/doc_api.html'><button class='btn doc api'>Documentación API</button></a><a target='_blank' href='../doc/doc_plugin.html'><button class='btn doc plugin'>Documentación Plugin</button></a><a target='_blank' href='../doc/JSON_doc.html'><button class='btn doc JSON'>Documentación Objetos JSON</button></a><h4>Plugins</h4>";
        for(var plugin of objeto_pl)
        {
            cont += "<div class='acc_plugin'><span class='titulo'>"+plugin.name+"</span><p class='parr'><b>Descripción:</b> "+plugin.description+"</p><p class='parr'><b>Autor:</b> "+plugin.author+"</p><p class='parr'><b>Imagen:</b><br /><img src='"+plugin.image+"' style='max-width:150px;width:80%;border-radius:5px;border:solid 1px darkgrey' /></p><p class='parr'><b>URL:</b> <a target='_blank' href='"+plugin.url+"'>"+plugin.url+"</a></p></div>";
        }
        cont += "<h4>API</h4><div class='api_row'><span class='us'>Usuarios Existentes:</span><select id='userlist'></select><button id='access_user'>Añadir Acceso</button></div><div class='api_row'><span class='us'>Usuarios con acceso a la API:</span><select id='accesslist'></select><button id='delete_user'>Quitar Acceso</button></div>";
        $(".content").html(cont);

        $("#access_user").click(anadirUsuario);
        $("#delete_user").click(quitarUsuario);

        $.get("api/?users").done(function(data){
            var sel = $("#userlist");
            for(var user of JSON.parse(data))
            {
                var html = sel.html();
                sel.html(html+"<option>"+user.nombre+"</option>");
            }
        });
        $.get("api/?access_user_api").done(function(data){
            var sel = $("#accesslist");
            var obj = JSON.parse(data);
            for(var us of obj.usuario)
            {
                var html = sel.html();
                sel.html(html+"<option value=',"+JSON.stringify(us)+"'>"+us.nombre+"</option>");
            }
        });
    }
    /**
     * Genera la vista para la Configuración
     */
    function Config()
    {
        $.get("api/?getConfig").done(function(data){
            var obj = JSON.parse(data);
            var cont = "<div class='configuration'><h3>Configuración</h3><input type='hidden' id='usuariotxt' value='"+JSON.stringify(obj.usuario)+"'/><div class='row'><span class='title'>Titulo de la Web:</span><input type='text' id='titulotxt' value='"+obj.titulo+"' /></div><div class='row'><span class='desc'>Descripción de la Web:</span><input type='text' id='descripciontxt' value='"+obj.descripcion+"' /></div><div class='row'><span class='keyword'>Palabras Claves de la Web:</span><input type='text' id='palabras_clavestxt' value='"+obj.palabras_claves+"' /></div><div class='row'><span class='temas'>Tema de la Web:</span><input type='text' id='tematxt' value='"+obj.tema+"' /></div><button class='save'>Guardar Configuración</button></div>";
            $(".content").html(cont);
            $(".save").click(btn_guardarConfig);
            });
    }
    /**
     * Guardar el fichero de configuración en un JSON 
     */
    function btn_guardarConfig()
    {
        var usuario = JSON.parse($("#usuariotxt").val());
        var json = 
        {
            "titulo":$("#titulotxt").val(),
            "descripcion":$("#descripciontxt").val(),
            "palabras_claves":$("#palabras_clavestxt").val(),
            "usuario":usuario,
            "tema":$("#tematxt").val()
        };
        var txtjson = JSON.stringify(json);
        $.post("api/",{set_config: txtjson}).done(function(info)
        {
            if(info)
            {
                alert("Guardado fichero de configuración con éxito");
            }else
            {
                alert("No se pudo guardar los cambios al fichero de configuración");
            }
        });
    }
    /**
     * Convierte el numero del rol en nombre  
     */
    function Rol(numero)
    {
        var num = parseInt(numero);
        switch(num)
        {
            case 1: return "Usuario";
            case 2: return "Colaborador";
            case 3: return "Editor";
            case 4: return "Administrador";
        }
    }
    /*
    * Convierte los espacios de una cadena en valores contiguos 
    */
    function espacios(str)
    {
        return str.replace(/\s/g,"");
    }
    /**
     * Ver una imagen en una nueva pestaña 
     */
    function VerImg() {
        window.open($(this).attr("src"), "_blank");
    }
    /**
     * Sube una imagen al hacer clic en el botón subir imagen 
     */
    function subirImagenes() {
        var form = new FormData();
        form.append("set_image", "setimage");
        form.append("image", $("#archivo").prop("files")[0]);
        $.ajax({
            url: "api/?set_image", // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData: false,        // To send DOMDocument or non processed data file it is set to false
            success: function (data)   // A function to be called if request succeeds
            {
                alert("Imagen súbida con éxito");
                $.get("api/?getImage").done(function (data) {
                    $(".imagen").html("");
                    for (var img of JSON.parse(data)) {
                        var antiguo = $(".imagen").html();
                        $(".imagen").html(antiguo + "<img src='img/client/" + img + "' style='width:128px;margin: 10px;' />");
                        if (titulo)
                            $(".imagen img").click(VerImg);
                        else
                            $(".imagen img").click(VerImg);
                    }
                });
            }
        });
    }
    /**
     * Añade un Cero a la izquierda del número cuando este es inferior a 10
     * @param {number} num 
     */
    function CeroIzq($num) {
        if ($num < 10)
            return "0" + $num;
        else
            return $num;
    }
    /**
     * Convierte la tabla con los diferentes accesos en un texto formateado que indica por cada página el número de visitas
     * @param {*} tabla 
     */
    function VerAcceso(tabla) {
        var txt = "";
        for (var linea of tabla) {
            var a = 0;
            var i = 0;
            for (var linea2 of tabla) {
                if (linea == linea2 && linea2 != "nulo") {
                    a++;
                }
                i++;
            }
            ReplaceAll(linea, "nulo", tabla);
            if (linea != "nulo")
                txt += "\nPágina: " + linea + ": " + a;
        }
        return txt;
    }
    /**
     * Reemplaza todos los valores coincidentes en un array
     */
    function ReplaceAll(valor, nuevo_valor, tabla) {
        var i = 0;
        for (var linea of tabla) {
            if (linea == valor) {
                tabla[i] = nuevo_valor;
            }
            i++;
        }
        return tabla;
    }
});