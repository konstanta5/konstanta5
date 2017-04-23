<h1><?=$title?></h1>

<div class="content m-b-1">
    <?=$row['content']?>
    <div class="m-b-1"></div>
    <?php if($row['time'] && ($row['content'] or $row['content_after'])) :?>
    <span class="small text-muted pull-xs-right">
        Опубликовано: 
        <time datetime="<?= date('Y-m-dTH:i:s', $row['time']) ?>"><?= date('d.m.Y H:i', $row['time']) ?></time>
    </span>
    <div class="small text-muted" itemscope itemtype="http://schema.org/Person" itemprop="author" id="author" title="Автор">
        <span class="fa fa-user" aria-hidden="true"></span>
        <span itemprop="name"><?= $row['author'] ?></span> 
    </div>
    <?php endif;?>
    
</div>

<?php if(in_array(App::$user->status, array('content','moderator','admin'))) :?>
<div class="m-y-1">
    <?=$view->renderView('_manager', array('row'=>$row))?>
</div>
<?php endif?>

<?=$view->setDir('main')->renderView('_social', array('title'=>$title));?>

<?php if(is_array($othernews) && count($othernews)): ?>
    <div class="other-news m-t-2">
        <h4>Читайте также</h4>
        <div class="row">
            <?php foreach($othernews as $val):?>
            <div class="col-xs-12 col-sm-6">
                <?= $view->setDir('news')->renderView('_anons', array('row'=>$val)); ?>
            </div>
            <?php endforeach;?>
        </div>
            
    </div>
<?php endif; ?>