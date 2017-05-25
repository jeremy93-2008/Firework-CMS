# Firework CMS API
La API de Firework CMS permite acceder a los datos de la página de una forma fácil a través de los diferentes protocolos HTTP existentes, siempre y cuando tenga el usuario los permisos necesarios para ello.

## GET/POST
Hay dos formas para poder autentificarse, a través de GET o a través de POST, aún asi se recomienda el uso de POST, ya que se maneja información que podría ser potencialmente sensible.

### Autentificación:
Genera el token de autorización necesario para usar la API
```http
.../api?username=[YouUser]&password=[YouPassword]
```
*return:*
```json
    {"Usuario conectado con éxito"}
    {"Usuario incorrecto"}
```

### Cerrar Sesión:
Cierra la Sesión explícitamente del usuario actualmente conectado a la API
```http
.../api?close
```
*return:*
```json
    {"Session closed"}
```

## GET
Todas las funciones que permiten recuperar información del CMS, se encuentran aqui.

### current_user:
Permite recuperar el usuario actual
```http
.../api?current_user
```
*return:*
```json
{usuario}
```

### current_user_role:
Permite recuperar el rol del usuario actual
```http
.../api?current_user_role
```
*return:*
```json
{rol}
```

### current_theme:
Permite recuperar el tema
```http
.../api?current_theme
```
*return:*
```json
{tema_ruta}
```

### current_theme_more:
Permite recuperar datos adicionales del tema
```http
.../api?current_theme_more
```
*return:*
```json
{nombre,descripcion,autor,url}
```

### current_title:
Permite recuperar el titulo actual de la web
```http
.../api?current_title
```
*return:*
```json
{titulo}
```

### statistic:
Permite recuperar las estadisticas del CMS, con filtro de fecha o no

Sin Filtro
```http
.../api?statistic
```
Con Filtro (desde *date* hasta *hoy*)
```http
.../api?statistic&date=[dd/mm/YYYY]
```
*return:*
```json
{estadistica:[{fecha,url},[...]]}
```

### page:
Permite recuperar una página del CMS, según el id escogido

```http
.../api?page=[id]
```
*return:*
```json
{name,id,content,image,autor,description,keyword}
```

### Allpage:
Permite recuperar todas las páginas del CMS

```http
.../api?Allpage
```
*return:*
```json
[{name,id,content,image,autor,description,keyword},[...]]
```

### article:
Permite recuperar un articulo del CMS, según el id escogido

```http
.../api?article=[id]
```
*return:*
```json
{name,id,content,image,autor,description,keyword}
```

### Allarticle:
Permite recuperar todos los articulos del CMS

```http
.../api?Allarticle
```
*return:*
```json
[{name,id,content,image,autor,access,description,category,date},[...]]
```
### GetCategory
Permite recuperar las categorias del CMS
```http
.../api?getCategory
```
*return:*
```json
{nombre,[...]}
```

### GetMenu
Permite recuperar el menu del CMS
```http
.../api?getMenu
```
*return:*
```json
[{titulo,orden,url},[...]]
```

### GetImage
Permite recuperar las imagenes almacenadas del CMS
```http
.../api?getImage
```
*return:*
```json
[{titulo,orden,url},[...]]
```

### PageNextId
Permite recuperar el proximo id en ser creado, a la hora de añadir una página
```http
.../api?pageNextId
```
*return:*
```json
{number}
```


### ArticleNextId
Permite recuperar el proximo id en ser creado, a la hora de añadir un articulo
```http
.../api?articleNextId
```
*return:*
```json
{number}
```

## POST
Todas las funciones que permiten el añadido, la edición o el borrado de la información del CMS se encuentran aqui.

### Set_article
Permite editar o añadir un articulo al CMS dependiendo del id pasado como parametro en el objeto JSON.
```http
.../api?{set_article:[JSON_Object]}
```
>*JSON_Object*: Mirar en el apartado __Articulo__ en la sección __Objetos JSON__
(No importa el orden de los parámetros)

*return:*
```json
{"Articulo guardado exitosamente"},
{"Fallo a la hora de guardar el Articulo"}
```

### Set_page
Permite editar o añadir una página al CMS dependiendo del id pasado como parametro en el objeto JSON.
```http
.../api?{set_page:[JSON_Object]}
```
>*JSON_Object*: Mirar en el apartado __Página__ en la sección __Objetos JSON__
(No importa el orden de los parámetros)

*return:*
```json
{"Página guardada exitosamente"},
{"Fallo a la hora de guardar la Página"}
```

### Del_article
Permite eliminar un articulo del CMS dependiendo del id pasado como parametro.
```http
.../api?{del_article:[id]}
```

*return:*
```json
{"Eliminación realizada con éxito"},
{"Un fallo se ha producido y no se pudo eliminar su contenido"}
```

### Del_page
Permite eliminar una página al CMS dependiendo del id pasado como parametro.
```http
.../api?{del_page:[id]}
```

*return:*
```json
{"Eliminación realizada con éxito"},
{"Un fallo se ha producido y no se pudo eliminar su contenido"}
```

### Set_image
Permite el añadido de una imagen en el CMS, está será accesible de igual modo que las otras imágenes insertadas normalmente
```http
.../api?{set_image:[_FILE]}
```
> *_FILE:* Objeto PHP ***$_FILE*** , que contiene la imagen que debe subirse con el nombre en el array asociativo del objeto PHP, como: "image"

*return:*
```json
{true},{false}
```

### Set_category
Permite añadir una categoria al CMS, para insertar posteriormente en un articulo.
```http
.../api?{set_category:[nombre]}
```

*return:*
```json
{true},{false}
```

### Add_menu
Permite añadir un menu al CMS.
```http
.../api?{add_mennu:[JSON_Object]}
```
>*JSON_Object*: Mirar en el apartado __Menú__ en la sección __Objetos JSON__
(No importa el orden de los parámetros)

*return:*
```json
{true},{false}
```

### Del_menu
Permite eliminar un menú del CMS, a partir del Id introducido.
```http
.../api?{del_menu:[id]}
```

*return:*
```json
{true},{false}
```

## Proximámente...
Se añadirán funcionalidades a cada nueva versión, consulte cada mes para estar seguro de no perder ninguna ventaja, que podría suplir un defecto o mejorar una opción...

