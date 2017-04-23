<ul class="list-group list-rows">
<?php foreach($rows as $row) : ?>
<li class="list-group-item">
    <?php if(in_array(App::$user->status, array('content','moderator','admin'))) :?>
    <div class="pull-xs-right">
    <?=$view->setDir('pages')->renderView('_manager', array('row'=>$row))?>
    </div>
    <?php endif ?>

    <a href="/<?=$row['chpu']?>"><?=$row['title']?></a>
    <?php if(in_array(App::$user->status, array('content','moderator','admin'))) :?>
    <div class="small text-muted"><?=$row['chpu']?></div>
    <?php endif?>
</li>
<?php endforeach?>
</ul>