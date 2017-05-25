<?php
/*
    Fw-Name: Lista de Articulos;
    Fw-Description: Todos los articulos juntos;
    Fw-Keywords: todos,articulo,juntos; 
    Fw-Author: admin,jeremy;
    Fw-Access: 0;
*/
?>
<strong>Pagina maestra <em>1</em></strong>
<?=$page->showUserLogin()?>
<?=$page->showUserRegister()?>
<?=$page->showArticleFromParam(array("name","content"),array(),true)?>