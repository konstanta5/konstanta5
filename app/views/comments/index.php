<?php
$view = new View();
$view->dir = 'comments';
?>
<h1><?=$title?></h1>

<div class="comments m-t-2">
    
    <div class="list">
        <?php if (is_array($rows) && count($rows) > 0) : ?>
        <?= $view->renderView('_list', array('comments' => $rows)) ?>
        <?php else :?>
        <div class="alert alert-warning">Отзывы не найдены</div>
        <?php endif; ?>
    </div>
    
</div>
