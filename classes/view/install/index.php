<html>
<head>
    <title><?=$page->fw_title?></title>
    <link rel="stylesheet" href="css/install.css">
    <script type="text/javascript" src=""></script>
</head>
<body>
    <div class="installer">
        <?php
            if(isset($_POST["installer"]))
            {
                $i = new Installer();
                $i->setName($_POST["title"]);
                $i->setDescription($_POST["descripcion"]);
                $i->setKeyword($_POST["keyword"]);
                $i->setUser($_POST["usuario"],md5($_POST["contrasenia"]),$_POST["rol"]);
                $i->setTheme($_POST["theme"]);
                $escrito = $i->writeconfig();
                if($escrito)
                {
                    echo "<div class='login'><h4>Ya se ha creado el fichero de configuración para el correcto funcionamiento de Firework, para poder visualizar su web
                    debe recargar esta página, o acceder <a href='.?admin'>Administración</a></h4></div>";
                }
            }else{
        ?>
        <form method="POST" action=".">
           <div class='login'>
                <img src="img/firework.png" />
                <h2>Bienvenido al instalador de Firework</h2>
                <input type='hidden' name="installer" value="1" />
                <h4>Info básica del sitio</h4>
                <table>
                    <tr>
                        <td><label>Titulo del sitio</label></td>
                        <td><input type="text" required name="title" /></td>
                    </tr>
                    <tr>
                        <td><label>Descripción del sitio</label></td>
                        <td><input type="text" required name="descripcion" /></td>
                    </tr>
                    <tr>
                        <td><label>Palabras Claves del sitio</label></td>
                        <td><input placeholder="palabra1,palabra2,palabra3,..." type="text" required name="keyword" /></td>
                    </tr>
                </table>
                <h4>Usuario</h4>
                <table>
                    <tr>
                        <td><label>Nombre de Usuario</label></td>
                        <td><input type="text" readonly required value="admin" name="usuario" /></td>
                    </tr>
                    <tr>
                        <td><label>Contraseña</label></td>
                        <td><input type="password" required name="contrasenia" /></td>
                    </tr>
                    <input type='hidden' name="rol" value="4" />
                </table>
                <h4>Tema principal</h4>
                <table>
                    <tr>
                        <td><label>Tema del sitio</label></td>
                        <td><input type="text" value="themes/tema" required name="theme" /></td>
                    </tr>
                </table>
                <div class="log">
                    <input class='btn' type='submit' value='Crear Configuración' />
                </div>
            </div>
        </form>
    </div>
        <?php } ?>
</body>
</html>