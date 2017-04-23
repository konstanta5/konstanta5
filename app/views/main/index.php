<h1><?= $title ?></h1>

<?php if ($row['content']) : ?>
    <?= $view->setDir('main')->renderView('_content', array('row' => $row)) ?>
    <?php // <time datetime="2017-01-15T14:51:43"> 14:51, 15 янв 2017 г.</time> ?>
<?php endif ?>

<?php if(is_array($rows)):?>
<section>
    <div class="row">
        <?php foreach ($rows as $row): ?>
            <div class="col-xs-12 col-sm-6">
                <?= $view->setDir('pages')->renderView('_anons', array('row'=>$row)); ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif;?>

<?= $view->setDir('main')->renderView('_social'); ?>