<?php
if (in_array(App::$user->status, array('moderator', 'admin'))) : 

    $hide_icon = ($row['hide'] == 1) ? 'fa-toggle-on text-danger' : 'fa-toggle-off text-muted';
    $rnd_resp = rand();
    ?>

    <a class="text-danger pull-xs-right m-l-1" href="/<?=Route::$controller?>/remove/<?= $row['id'] ?>" data-type="ajax" data-before="removeConfirm" data-after="removeComment" title="Удалить"><span class="fa fa-trash"></span></a>

    <a class="text-danger pull-xs-right" href="/news/hide/<?= $row['id'] ?>" data-type="ajax" data-alert=".resp_<?= $rnd_resp ?>" data-hide="<?= $row['hide'] ?>" data-after="newsHide" title="Вкл/выкл">
        <span class="fa <?= $hide_icon ?>" aria-hidden="true"></span></a>
    <span class="resp_<?= $rnd_resp ?>"></span>
    
<?php endif?>
<span class="small">
    <?php
    $class['content'] = ($row['count']['content'] >= 850) ? 'text-success':'text-warning';
    $class['description'] = ($row['count']['description'] >= 70 && $row['count']['description'] <= 160) ? 'text-success':'text-warning';
    $class['keywords'] = ($row['count']['keywords'] >= 160) ? 'text-success':'text-warning';
    ?>
    <span class="fa fa-file-o <?= $class['content'] ?>" title="Контент, количество символов"></span> <?=$row['count']['content']?>

    <span class="fa fa-file-text-o <?= $class['description'] ?>" title="Описание, количество символов"></span> <?=$row['count']['description']?>

    <span class="fa fa-key <?= $class['keywords'] ?>" title="Ключевики, количество символов"></span> <?=$row['count']['keywords']?>
</span>

<a href="/<?=Route::$controller?>/edit/<?= $row['id'] ?>" title="Редактировать"><span class="fa fa-pencil-square-o"></span></a>
