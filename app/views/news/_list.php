<ul class="list-group list-rows list-news">
<?php foreach($rows as $row) : ?>
<li class="list-group-item">
    <?php if(in_array(App::$user->status, array('content','moderator','admin'))) :?>
    <span class="pull-xs-right">
    <?=$view->renderView('_manager', array('row'=>$row))?>
    </span>
    <?php endif ?>
    
    <div class="small text-muted">
        <span class="fa fa-calendar" aria-hidden="true"></span>
        <time datetime="<?=date('Y-m-dTH:i:s', $row['time'])?>"><?=date('d.m.y H:i:s', $row['time'])?></time>
    </div>
    <img class="img-thumbnail" src="/img/news/<?=$row['tizer']?>" alt="">
    <a href="/news/read/<?=$row['id']?>"><?=$row['title']?></a>
    
</li>
<?php endforeach?>
</ul>