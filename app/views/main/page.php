<?php if($row['content']) :?>
    <h1><?= $title ?></h1>
    <?=$view->setDir('main')->renderView('_content', array('row'=>$row))?>
<?php else :?>
    <?=$view->setDir('404')->renderView('error', array('title'=>'404 страница не найдена', 'msg'=>'Ошибка 404, страница не найдена'))?>
<?php endif?>

<?php if($row['content_after']) :?>
<div class=""><?=$row['content_after']?></div>
<?php endif?>