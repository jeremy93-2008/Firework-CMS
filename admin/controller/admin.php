<?php

function showAdmin()
{
    $_CONF = json_decode(file_get_contents("config/config.json"));

    ?>
    <div class='sidebar'>
        <input type="hidden" id="usuario" value="<?=$_SESSION['usuario']['user']?>"/>
        <div class='titulo'>
            <h4><?=$_CONF->titulo?></h4>
            <h6><?=$_SERVER["HTTP_HOST"]?> (<span id="user_id"><?=$_SESSION['usuario']['user']?></span>) <span id='cerrar'>Cerrar Sesión</span></h6>
        </div>
        <button class="btn side selecc">Estadistica</button>
        <p class='submenu'>Publicar</p>
        <button class="btn side">Páginas</button>
        <button class="btn side">Articulos</button>
        <button class="btn side">Medios</button>
        <button class="btn side">Comentarios</button>
        <?php
            $u = new Users();
            $rol = $u->getCurrentUser()->rol;
            if($rol > 2){
        ?>
        <p class='submenu'>Personalizar</p>
        <button class="btn side">Temas</button>
        <button class="btn side">Menú</button>
        <?php if($rol>3){?>
        <p class='submenu'>Configurar</p>
        <button class="btn side">Usuarios</button>
        <button class="btn side">Plugins/API</button>
        <button class="btn side">Configuración</button>
        <?php
            if(Plugin::hasPlugin())
            {
                ?><p class="submenu">Plugin</p><?php
                $arr = Plugin::infoPlugin();
                ?><div class='infoPlugin' style='display:none;'><?=json_encode($arr)?></div><?php
                $a = 0;
                foreach($arr as $field)
                {
                    ?><button idPlugin='<?=$a?>' class="btn side plugin"><?=$field->name?></button>
                    <?php
                    $a++;
                }
            }
        ?>
        <?php } } ?>
    </div>
    <div class="content">
    </div>
    <?php
        if(Plugin::hasPlugin())
        {
            echo Plugin::AdminView();
        }
}