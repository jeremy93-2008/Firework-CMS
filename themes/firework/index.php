<?=$page->showHeader()?>
<?php 
    if($page->isHome()){
        $p = Plugin::instanceClass("Slideshow Plugin");
        $p->showSlide();
    }
?>
<?php if($page->isHome()){ ?>
<div class="header">
    <a style="padding-left: 22px;" class='big' href='.'><img src="img/firework.png" style="height:85px;display:inline-block;" /></a>
    <a style="padding-left: 22px;display:none;" class='small' href='.'><img src="img/firework.png" style="height:85px;display:inline-block;" /></a>
    <?=$page->showMenu()?>
</div>
<div class="head">
    <h1>Firework CMS</h1>
    <p>Make Website Great Again! With a Awesome File-based CMS</p>
    <a href='img/client/Firework_CMS.zip'><button>Download Now (v.1.0)</button></a>
    <a href='#learn' class='learn'><button class='btn'>Learn More</button></a>
</div>
<div class='flecha'>
    <i class='fa fa-arrow-circle-down' arua-hidden='true'></i>
</div>
<div id='learn' class='mainIndex'>
    <h1>Firework CMS, a new way to make Website</h1>
    <?=$page->showArticle(array("content"))?>
</div>
<?php }else{ ?>
    <div class="header otherheader">
        <a style="padding-left: 22px;" class='big' href='.'><img src="img/firework.png" style="height:85px;display:inline-block;" /></a>
        <a style="padding-left: 22px;display:none;" class='small' href='.'><img src="img/firework.png" style="height:85px;display:inline-block;" /></a>
        <?=$page->showMenu()?>
    </div>
    <div class='mainOther'>
        <?=$page->showArticleRangeFromParam(array("name","date","content"),10);?>
    </div>
<?php } ?>
<?=$page->showFooter()?>