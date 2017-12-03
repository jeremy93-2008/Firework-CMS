# Firework Template
El CMS permite crear plantillas para crear una realidad visual y aumentar la estética base de este, para ello, los desarolladores tienen acceso a múltiples funciones, los **$Page**, esto está coordinado por un fichero JSON único por cada template.

## ¿Como se crea un template?
Primeramente debe haber visto que en la jerarquía raiz, existe ya una carpeta *plugin*, si entra dentro verá ya una carpeta que sirve de plugin de ejemplo, donde verá lo siguiente:

* *theme.json*: Corresponde con el fichero de configuración necesario para hacer funcionar nuestro template.

* *index.php*: Corresponde con el PHP inicial que se cargará a la hora de inicializar el template.

Para crear un nuevo template, lo único que debemos hacer es crear una nueva carpeta dentro de la carpeta themes y copiar el contenido del tema de ejemplo a la nueva carpeta, también puede optar por crear su propio JSON desde cero, en ese caso necesita saber varias cosas:

### theme.json
Un fichero Template debe tener la estructura siguiente:

```json
{
	"nombre":"Nombre del template",
	"descripcion":"Descripción de este",
	"author":"Autor del contenido",
	"imagen":"Imagen promocional de vuestra creación",
	"url":"URL para consultar más de sus trabajos"
}
```

## $page
Para facilitar tu trabajo a la hora de construir una plantilla y tener acceso al CMS, hemos puesto a tu disposición diferentes funciones que te permitirá añadir contenido dinamico a tu plantilla.

Esta función devuelve el contenido de *header.php* del tema actual.
```php
<?=$page->showHeader()?>
```
Esta función devuelve el contenido de *sidebar.php* del tema actual.
```php
<?=$page->showSideBar()?>
```
Esta función devuelve el contenido de *footer.php* del tema actual.
```php
<?=$page->showFooter()?>
```
Esta función devuelve el contenido de *404.php* del tema actual.
```php
<?=$page->showError()?>
```
Esta función devuelve el contenido de *[$nombre_del_fichero].php* del tema actual.
```php
<?=$page->showView($nombre_del_fichero)?>
```
Esta función devuelve el articulo que establece la propiedad $_GET['ac'], sino se encuentra, por defecto es 1.
```php
<?=$page->showArticle(array("name","date","image","content","author","category","description"),[array("search_by_name","search_by_date"...)])?>
```
Esta función devuelve la página que establece la propiedad $_GET['pa'], sino se encuentra, por defecto es 1.
```php
<?=$page->showPage(array("name","date","image","content","author","category","description"),[array("search_by_name","search_by_date"...)])?>
```
Esta función devuelve el articulo que establece la propiedad $_GET['ac'], sino se encuentra, devuelve showAllArticles().
```php
<?=$page->showArticleFromParam(array("name","date","image","content","author","category","description"),[array("search_by_name","search_by_date"...)],[enlaceHaciaArtIndividuales:boolean])?>
```
Esta función devuelve todos los articulos del CMS, dentro de un rango númerico.
```php
<?=$page->showRangeArticles(array("name","date","image","content","author","category","description"),[numArtCadaPagina],[array("search_by_name","search_by_date"...)],[enlaceHaciaArtIndividuales:boolean])?>
```
Esta función devuelve el articulo que establece la propiedad $_GET['ac'], sino se encuentra, devuelve showRangeArticles().
```php
<?=$page->showArticleRangeFromParam(array("name","date","image","content","author","category","description"),[numArtCadaPagina],[array("search_by_name","search_by_date"...)],[enlaceHaciaArtIndividuales:boolean])?>
```
Esta función devuelve todos los articulos del CMS, ordenados por fecha de publicación.
```php
<?=$page->showAllArticles(array("name","date","image","content","author","category","description"),[array("search_by_name","search_by_date"...)],[enlaceHaciaArtIndividuales:boolean])?>
```
Esta función devuelve todas las paginas del CMS, ordenados por ID.
```php
<?=$page->showAllPages(array("name","date","image","content","author","category","description"),[array("search_by_name","search_by_date"...)])?>
```
Esta función devuelve el formulario de inicio de sesión. (2 estados posibles: formulario -o- informacion del usuario registrado)
```php
<?=$page->showUserLogin()?>
```
Esta función devuelve el formulario de registro de nuevo usuario.
```php
<?=$page->showUserRegister()?>
```
Esta función devuelve el menu del CMS.
```php
<?=$page->showMenu()?>
```
Esta función devuelve true si se esta en la página principal o false, si hay algún parametro indicado.
```php
<?=$page->isHome()?>
```