# Firework API
La API de Firework CMS permite acceder a los datos de la página de una forma fácil a través de los diferentes protocolos HTTP existentes, siempre y cuando tenga el usuario los permisos necesarios para ello.

## GET

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

