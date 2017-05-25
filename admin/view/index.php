<?php
include 'admin/controller/admin.php';
    if(file_exists("config/config.json"))
    {
    $_CONF = json_decode(file_get_contents("config/config.json"));
    $bool = true;
?>
<!doctype>
<html>
    <head>
        <title>Administración de <?=$_CONF->titulo?></title>
        <link rel="stylesheet" href="css/admin.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="js/admin.js"></script>
        <script type="text/javascript" src="js/admin/article.js"></script>
        <script type="text/javascript" src="js/admin/page.js"></script>
        <script type="text/javascript" src="js/admin/statistic.js"></script>
        <script type="text/javascript" src="js/admin/menu.js"></script>
        <script type="text/javascript" src="js/admin/comment.js"></script>
        <script type="text/javascript" src="js/admin/user.js"></script>
        <script type="text/javascript" src="js/admin/api.js"></script>
        <script type="text/javascript" src="js/admin/theme.js"></script>
        <script type="text/javascript" src="js/textboxio.js"></script>
        <script type="text/javascript" src="js/resources/emmet.min.js"></script>
    </head>
    <body>
<?php
    if(isset($_SESSION["usuario"]) && isset($_COOKIE["token_auth"]))
    {
        $rol = new Rol();
        $num = $rol->retrieveRolByName($_SESSION["usuario"]["user"]);
        if($num <= 1)
        {
            $bool = false;
        }
        if($bool)
        {
            showAdmin();
        }else
        {
            ?>
            <form action='#' method="POST">
                <div class='login'>
                    <img src="img/firework.png" />
                    <h2>Administración de  <?=$_CONF->titulo?></h2>
                    <h4>Error: Usuario o Contraseña incorrecta</h4>
                    <input type="hidden" name="login" value="1" />
                    <table>
                        <tr>
                            <td><label for="username">Nombre de Usuario</label></td>
                            <td><input type="text" required name="username" /></td>
                        </tr>
                        <tr>
                            <td><label for="password">Contraseña</label></td>
                            <td><input type="password" required name="password" /></td>
                        </tr>
                    </table>
                    <div class="log">
                        <input class='btn' type="submit" value="Iniciar Sesión" />
                    </div>
                </div>
            </form>
            <?php
        }
    }
    else if(isset($_POST["login"]))
    {
        $us = new Users();
        $bool = $us->loginUser($_POST["username"],$_POST["password"]);
        $rol = new Rol();
        $num = $rol->retrieveRolByName($_POST["username"]);
        if($num <= 1)
        {
            Users::closeUser();
            $bool = false;
        }
        if($bool)
        {
            showAdmin();
        }else
        {
            ?>
            <form action='#' method="POST">
                <div class='login'>
                    <img src="img/firework.png" />
                    <h2>Administración de  <?=$_CONF->titulo?></h2>
                    <h4>Error: Usuario o Contraseña incorrecta</h4>
                    <input type="hidden" name="login" value="1" />
                    <table>
                        <tr>
                            <td><label for="username">Nombre de Usuario</label></td>
                            <td><input type="text" required name="username" /></td>
                        </tr>
                        <tr>
                            <td><label for="password">Contraseña</label></td>
                            <td><input type="password" required name="password" /></td>
                        </tr>
                    </table>
                    <div class="log">
                        <input class='btn' type="submit" value="Iniciar Sesión" />
                    </div>
                </div>
            </form>
            <?php
        }
    }else
    {
        ?>
        <form action='#' method="POST">
            <div class='login'>
                <img src="img/firework.png" />
                <h2>Administración de  <?=$_CONF->titulo?></h2>
                <input type="hidden" name="login" value="1" />
                <table>
                    <tr>
                        <td><label for="username">Nombre de Usuario</label></td>
                        <td><input type="text" required name="username" /></td>
                    </tr>
                    <tr>
                        <td><label for="password">Contraseña</label></td>
                        <td><input type="password" required name="password" /></td>
                    </tr>
                </table>
                <div class="log">
                    <input class='btn' type="submit" value="Iniciar Sesión" />
                </div>
            </div>
        </form>
        <?php
    }
    }else
    {
        ?><script>location.href='index.php'</script><?php
    }
?>
    </body>
</html>