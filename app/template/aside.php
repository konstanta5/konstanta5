<?php if($aside):?>

    <?php if(is_array($aside['lastpages']) && count($aside['lastpages'])):?>
    <div class="block last-pages">
    <?php foreach($aside['lastpages'] as $row):
        if($row['id'] == Route::$routes[3]){continue;}
    ?>
        <?= $view->setDir('pages')->renderView('_anons', array('row'=>$row)); ?>
    <?php endforeach;?>
    </div>
    <?php endif;?>

<?php endif;?>