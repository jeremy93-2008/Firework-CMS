<!doctype>
<html>
<head>
    <title><?=$page->fw_title?></title>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" ></script>
    <link rel='stylesheet' href='<?=$page->getThemePath()?>/css/style.css' />
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' />
    <script type='text/javascript' src='<?=$page->getThemePath()?>/js/script.js'></script>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <?=$page->showMenu();?>
        </div>
        <div class='frame'>