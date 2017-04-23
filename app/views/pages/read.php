<h1><?=$title?></h1>

<div class="content">
<?=$row['content']?>
    
    <div class="<?=(in_array(App::$user->status, array('content','moderator','admin'))) ? '': 'hidden-xs-up'?>">
    <div class="small text-muted" itemscope itemtype="http://schema.org/Person" itemprop="author" id="author" title="Автор">
        <span class="fa fa-user" aria-hidden="true"></span>
        <span itemprop="name"><?= $row['author'] ?></span> 
    </div>
    <?php if($row['edit_time'] && ($row['content'] or $row['content_after'])) :?>
    <span class="small text-muted">
        Последнее изменение: 
        <time datetime="<?= date('Y.m.d H:i:s', $row['edit_time']) ?>"><?= date('d.m.Y', $row['edit_time']) ?></time>
    </span>
    <?php endif;?>
    </div>
    
</div>

<?php if(in_array(App::$user->status, array('content','moderator','admin'))) :?>
<div class="m-b-1">
    <?=$view->renderView('_manager', array('row'=>$row))?>
</div>
<?php endif?>

<?=$view->setDir('main')->renderView('_social', array('title'=>$title));?>