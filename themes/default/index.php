<?=$page->showHeader()?>
<?php if($page->isHome()){ ?>
    <?=$page->showAllArticles(array("content"),array("Bienvenido"),true,true);?>
<?php }else{ ?>
    <?=$page->showArticleRangeFromParam(array("content"),10,array());?>
<?php } ?>
<?=$page->showFooter()?>