## Objetos JSON de Firework CMS

### Estructura JSON de Articulos:
```json
{
    "id":"Corresponde a la identificación única del 
    articulo, debe ser un entero",
    "name":"nombre del articulo",
    "description":"descripcion del articulo",
    "date":"Fecha de la ultima edición [dd/mm/YYY]",
    "image":"Inserte una URL para visualizar la imagen 
    destacada del articulo",
    "content":"Contenido HTML del articulo",
    "autor":"Autor/es del articulo, pueden existir 
    varios separados por comas. 
    p.ej: Paco || Paco,Antonio",
    "access":"Acceso a la hora de visualizar los 
    articulos, puede contener tanto nombres de usuarios,
    como numero de roles, separados por comas. P.ej: 
    paco,2,jeremy",
    "deny":"Denegación a la hora de visualizar los articulos, puede contener tanto 
    nombres de usuarios, como numero de roles, separados por comas. P.ej: 
    paco,2,jeremy ",
    "category":"Debe ser un entero que concorde con una 
    de las categorias creadas previamente en el CMS.",

}
```

### Estructura JSON de Páginas:
```json
{
    "id":"Corresponde a la identificación única de la 
    página, debe ser un entero",
    "name":"nombre de la página",
    "description":"descripcion de la página",
    "image":"Inserte una URL para visualizar la imagen 
    destacada de la página",
    "content":"Contenido HTML de la página",
    "autor":"Autor/es de la página, pueden existir 
    varios separados por comas. 
    p.ej: Paco || Paco,Antonio",
    "keyword":"Palabras claves separadas por comas"
}
```

### Estructura JSON del Menú
```json
{
    "titulo":"Titulo que se visualizará en el menú",
    "orden":"Orden dentro del Menú. Debe ser un entero",
    "url":"Destino del Menú"
}
```

### Estructura JSON del Usuario
```json
{
    "nombre":"Nombre de Usuario",
    "contrasenia":"Contraseña del usuario almacenada en 
    MD5",
    "rol":"A que grupo de usuarios pertenece, debe ser 
    un entero"
}
```
### Roles
```
[
    0: Anonimo
    1: Usuario normal
    2: Colaboradores
    3: Editores
    4: Administradores
]
```