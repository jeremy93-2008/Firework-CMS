# Firework Plugin Access
El CMS permite crear plugins para completar y aumentar las funcionalidades bases de este, para ello, los desarolladores tienen acceso a múltiples funciones, que se diferencian en dos grandes categorias, **Hooks** e **Interplays**, y esto está coordinado por un fichero JSON único por cada plugin.

## ¿Como se crea un plugin?
Primeramente debe haber visto que en la jerarquía raiz, existe ya una carpeta *plugin*, si entra dentro verá ya una carpeta que sirve de plugin de ejemplo, donde verá lo siguiente:

* *Plugin.json*: Corresponde con el fichero de configuración necesario para hacer funcionar nuestro plugin.

* *Main.php*: Corresponde con el PHP inicial que se cargará a la hora de inicializar el plugin, este puede tener otro nombre, ya que es totalmente configurable gracias al JSON visto anteriormente.

Para crear un nuevo plugin, lo único que debe hacer es crear una nueva carpeta dentro de la carpeta plugin y copiar el contenido del plugin de ejemplo a la nueva carpeta, también puede optar por crear su propio JSON desde cero, en ese caso necesita saber varias cosas:

### Plugin.json
Un fichero Plugin debe tener la estructura siguiente:

```json
{
	"name":"Nombre del Plugin",
	"description":"Descripción de este",
	"author":"Autor del contenido",
	"image":"Imagen promocional de vuestra creación",
	"url":"URL para consultar más de sus trabajos",
	"file":"Archivo Inicial que se cargará al 
    inicializarse el plugin. p.ej: main.php",
	"mainClass":"Clase que se cargará dentro del 
    fichero a la hora de inicializar el plugin. p.ej: 
    NuevoMenu"
}
```
### file.php
El fichero PHP que se inicializará a la hora de cargarse su plugin.
```php
<?php
    class mainClass
    {
        /* Todas las funciones necesarias al buen 
        funcionamiento del plugin*/
        
        // Hook
        public function init() 
        {
            // Interplay
            Plugin::Article(1)

            // Se ejecutará en el momento de inicializarse por primera vez
        }

        [...]
    }
```

Ahora que sabemos como crear un Plugin en Firework vamos a ver lo que podemos realizar con su interfaz.

## Clase principal del plugin
Una vez cargado nuestro plugin en Firework CMS, podemos llamar su clase maestra desde cualquier sitio de Firework, para posteriormente usarla para lo que plazcas como ejecutar una función, recuperar una variable, etc...

Asi para crear una instancia de clase deberemos:

```php
    Plugin::instanceClass($nombre_del_plugin)
```

Esto nos devolverá nuestra clase principal instanciada, que deberemos recuperar en una variable, para poder usarla a lo largo de la aplicación sin preocuparnos del *scope*.

## Hooks
Estos son los ganchos (funciones) que permiten hacer ejecutar nuestro código en un momento dado de la carga del CMS, por ejemplo, podríamos decirle de realizar una función antes de que se cargue el encabezado de la página o antes que se cargue el menú, también podriamos hacerlo recursivo y pedir que se cargue una función por cada articulo que se muestre en la web, etc..

Pero los hooks no nos permite sólo ejecutar funciones en un momento dado del ciclo de vida de la página, sino que también nos permite visualizar los resultados de tal función, simplemente añadiendo tantas funciones **echo** como queramos en los mismos métodos que realizan una función en un momento dado.

Para ello se tiene *27 Hooks*, donde cada uno corresponde con un estado dado del CMS:

### Init
Hook que se carga cuando el sitio web inicia una nueva sesión.
```javascript
    function init(){
        //Codigo que se cargará al iniciar una nueva sesión
        }
```
### Reload
Hook que se carga a cada refresco de la página, menos la primera vez que se inicia una nueva sesión, ya que *init* tiene prioridad sobre *reload*.
```javascript
    function reload(){
        //Codigo que se cargará al recargar el sitio en una misma sesión
        }
```

### Ready
Hook que se carga cuando el sitio web a terminado de cargarse completamente
```javascript
    function ready(){
        //Codigo que se cargará al recargar el sitio en una misma sesión
        }
```

### beforeHeader
Hook que se carga antes de la visualización del header.php del template del CMS.
```javascript
    function beforeHeader(){
        //Codigo
        }
```
### afterHeader
Hook que se carga después de la visualización del header.php del template del CMS.
```javascript
    function afterHeader(){
        //Codigo
        }
```
### beforeMenu
Hook que se carga antes de la visualización del menú del CMS.
```javascript
    function beforeMenu(){
        //Codigo
        }
```
### afterMenu
Hook que se carga después de la visualización del menú del CMS.
```javascript
    function afterMenu(){
        //Codigo
        }
```
### beforeLoginForm
Hook que se carga antes de la visualización del *User Login* del CMS.
```javascript
    function beforeLoginForm(){
        //Codigo
        }
```
### afterLoginForm
Hook que se carga después de la visualización del *User Login* del CMS.
```javascript
    function afterLoginForm(){
        //Codigo
        }
```
### beforeRegisterForm
Hook que se carga antes de la visualización del *User Register* del CMS.
```javascript
    function beforeRegisterForm(){
        //Codigo
        }
```
### afterRegisterForm
Hook que se carga después de la visualización del *User Register* del CMS.
```javascript
    function afterRegisterForm(){
        //Codigo
        }
```
### beforeArticle
Hook que se carga antes de la visualización de un articulo del CMS.
```javascript
    function beforeArticle(){
        //Codigo
        }
```
### afterArticle
Hook que se carga después de la visualización de un articulo del CMS.
```javascript
    function afterArticle(){
        //Codigo
        }
```
### beforeComment
Hook que se carga antes de la visualización de los comentarios de cada articulo del CMS.
```javascript
    function beforeComment(){
        //Codigo
        }
```
### afterComment
Hook que se carga después de la visualización de los comentarios de cada articulo del CMS.
```javascript
    function afterComment(){
        //Codigo
        }
```
### beforePage
Hook que se carga antes de la visualización de una página del CMS.
```javascript
    function beforePage(){
        //Codigo
        }
```
### afterPage
Hook que se carga después de la visualización de una página del CMS.
```javascript
    function afterPage(){
        //Codigo
        }
```
### beforeSideBar
Hook que se carga antes de la visualización del sidebar.php del template del CMS.
```javascript
    function beforeSideBar(){
        //Codigo
        }
```
### afterSideBar
Hook que se carga después de la visualización del sidebar.php del template del CMS.
```javascript
    function afterSideBar(){
        //Codigo
        }
```
### beforeFooter
Hook que se carga antes de la visualización del footer.php del template del CMS.
```javascript
    function beforeFooter(){
        //Codigo
        }
```
### afterFooter
Hook que se carga después de la visualización del footer.php del template del CMS.
```javascript
    function afterFooter(){
        //Codigo
        }
```
### beforeView
Hook que se carga antes de la visualización de una vista del CMS.
```javascript
    function beforeView(){
        //Codigo
        }
```
> *Las Vistas* son los diferentes PHP que pueden ser cargados por una plantilla, puede ser: index.php, main.php, about.php etc...

### afterView
Hook que se carga después de la visualización de una vista del CMS.
```javascript
    function afterView(){
        //Codigo
        }
```
> *Las Vistas* son los diferentes PHP que pueden ser cargados por una plantilla, puede ser: index.php, main.php, about.php etc...

### logging
Hook que se carga cuando se ha iniciado sesión en el CMS.
```javascript
    function logging(){
        //Codigo
        }
```

### registering
Hook que se carga cuando se ha registrado un nuevo usuario en el CMS.
```javascript
    function registering(){
        //Codigo
        }
```
---
### showAdminView
Hook que se carga cuando se está en el panel de administración y se le hace clic al botón correspondiente a su plugin.
```javascript
    function showAdminView(){
        //Codigo
        }
```

## Interplays
Asi como los Hooks eran los ganchos que permitián realizar acciones dentro del CMS, estos son los que interracionan con el CMS para obtener,añadir,modificar y eliminar información de este, por tanto los Interplays se usan para toda la interacción necesaria entre nuestro Plugin y los datos de nuestro CMS.

Hay 10 Interplays diferentes, de ello se diferencian tres tipos: *CRUD Interplays* , *Users Interplays* , *Getters Interplays*.

### CRUD Interplays

**Artículo**

```php
    Plugin::Article($id_articulo,$operacion,$json_editado)
```

Este metodo tiene cuatro sobrecargas:

```php
    Plugin::Article() 
    // Obtiene todos los articulos.
```
```php
    Plugin::Article(2) 
    // Obtiene el articulo del id estipulado.
```
```php
    Plugin::Article(2,["get","set","del"]) 
    // Se elije una de las tres operaciones disponibles, y según esta se realizará una función determinada sobre el id especificado.
```
```php
    Plugin::Article(2,"set",[JSON_Object]) 
    //En ese caso, el tercer parametro solo sirve para la operación set, y sirve para añadir o moficar un articulo a partir del JSON enviado.
```

>*JSON_Object*: Mirar en el apartado __Articulo__ en la sección __Objetos JSON__

**Página**

```php
    Plugin::Page($id_page,$operacion,$json_editado)
```

Este método tiene cuatro sobrecargas:

```php
    Plugin::Page() 
    // Obtiene todas las páginas.
```
```php
    Plugin::Page(2) 
    // Obtiene la página del id estipulado.
```
```php
    Plugin::Page(2,["get","set","del"]) 
    // Se elije una de las tres operaciones disponibles, y según esta se realizará una función determinada sobre el id especificado.
```
```php
    Plugin::Page(2,"set",[JSON_Object]) 
    //En ese caso, el tercer parametro solo sirve para la operación set, y sirve para añadir o modificar una página a partir del JSON enviado.
```

>*JSON_Object*: Mirar en el apartado __Página__ en la sección __Objetos JSON__

**Imagen**

```php
    Plugin::Image($id_image,$operacion,$file)
```

Este método tiene cuatro sobrecargas:

```php
    Plugin::Image() 
    // Obtiene todas las imágenes.
```
```php
    Plugin::Image(2) 
    // Obtiene la imagen del id estipulado.
```
```php
    Plugin::Image(2,["get","set"]) 
    // Se elije una de las dos operaciones disponibles, y según esta se realizará una función determinada sobre el id especificado.
```
```php
    Plugin::Image(2,"set",[$File]) 
    //En ese caso, el tercer parametro solo sirve para la operación set, y sirve para añadir o modificar una página a partir de un objeto FILE de PHP.
```

>*$FILE*: Objeto PHP ***$_FILE*** , que contiene la imagen que debe subirse con el nombre en el array asociativo del objeto PHP, como: "image"

### Users Interplays

**isLogged**
```php
    // Determina si está logeado algún usuario
    Plugin::isLogged() : Booleano
```
**CurrentUser**
```php
    // Determina el usuario actualmente conectado
    Plugin::CurrentUser() : JSON_Object
```
>*JSON_Object:* Mirar en el apartado __Usuario__ en la sección __Objetos JSON__

**Login**
```php
    // Logea un usuario con los parametros introducidos
    Plugin::Login($username,$password) : Booleano
```

**Register**
```php
    // Determina si está logeado algún usuario
    Plugin::Register($username,$password,$password2,$rol) : Booleano
```

Este método tiene dos sobrecargas:

```php
    Plugin::Register("usuario","abcdef","abcdef") : Booleano
    // Registra un usuario con el nombre de usuario y las contraseñas introducidas, con el rol por defecto, que es, 1
```
>*Rol:* Mirar en el apartado __Rol__ en la sección __Objetos JSON__

```php
    Plugin::Register("usuario","abcdef","abcdef",[0,1,2,3,4]) : Booleano
    // Registra un usuario con el nombre de usuario y las contraseñas introducidas, y con el rol establecido.
```
>*Rol:* Mirar en el apartado __Rol__ en la sección __Objetos JSON__

### Getters Interplays

**View**
```php
    // Determina el fichero cargado como principal en el template
    Plugin::View() : String
```

**Get**
```php
    // Determina el fichero cargado como principal en el template
    Plugin::Get($param_name) : String or String[]
```
Este método tiene dos sobrecargas:

```php
    Plugin::Get() : String[]
    // Devuelve todo el array de la variable global $_GET
```

```php
    Plugin::Get("pa") : String
    // Devuelve el parametro 'pa' ubicado en la variable global $_GET
```
**WebsiteInfo**
```php
    // Obtiene las diferentes configuraciones del CMS
    Plugin::WebsiteInfo(["titulo","descripcion","palabras_claves","tema"]) : String or String[]
```
Este método tiene dos sobrecargas:

```php
    Plugin::WebsiteInfo() : String[]
    // Devuelve todo el array de configuración del CMS
```

```php
    Plugin::WebsiteInfo("titulo") : String
    // Devuelve el parametro 'titulo' de la configuración del CMS
```

## Próximamente
El Firework Plugin Access sigue creciendo, pronto nuevas funcionalidades, etc...

