<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?=$title?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="<?=$description?>" />
    <meta name="keywords" content="<?=$keywords?>" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />

    <?=$opengraph?>
    
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" integrity="sha384-MIwDKRSSImVFAZCVLtU0LMDdON6KVCrZHyVQQj6e8wIEJkW4tvwqXrbMIya1vriY" crossorigin="anonymous">
    
    <link rel="stylesheet" href="/css/style.css" type="text/css">   
</head>
<body>

    <div class="container-fluid">
        <div class="wrap-container">
            <header>
                <?=App::param('site_name')?>
            </header>
            
        <?=$nav?>
        <?=$develop?>
            
        <div class="wrap">
            <main>
                <div class="<?=($aside == false)?'':'article'?> <?=  Route::$controller?>">
                    <?=$alert?>
                    <?=$breadcrumbs?>
                    <?=$content?>
                </div>
            </main>
            <?php if($aside != false):?>
            <div class="asides">
                <aside class="right"><?=$aside?></aside>
            </div>
            <?php endif?>
            <div class="clearfix"></div>
        </div>
            
        <?=$footer?>
        </div>
    </div>
    

    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
 

    <script src="/js/jquery-3.1.0.min.js"></script>
    <script src="/js/tether.min.js"></script><!-- Tether for Bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous"></script>

    <script src="/js/script.js"></script>
    <?php if(in_array(App::$user->status, array('content','moderator','admin'))) :?>    
        <script src="/lib/editor/jquery.wysibb.min.js"></script>
        <script src="/lib/editor/lang/ru.js"></script>
        <script src="/lib/editor/options.init.js"></script>
        <link rel="stylesheet" href="/lib/editor/theme/default/wbbtheme.css" />
        <script>
            $(document).ready(function() {
                //var wbbOpt = {imgupload: true, img_uploadurl: "/admin.php/site/imageUpload/"};
                $('#content').wysibb(wbbOpt).attr('id','wbbEditorContent');
                //$('#content_after').wysibb(wbbOpt);
                //$('textarea[name="content"], textarea[name="content_after"]').execCommand("headers");
            });
        </script>
    <?php endif?>
    <?php if(!in_array(App::$user->status, array('content','moderator','admin'))) :?>
        	
    <?php endif?>
</body>
</html>