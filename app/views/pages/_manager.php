<a class="text-danger pull-xs-right m-l-1" href="/pages/remove/<?= $row['id'] ?>" data-type="ajax" data-before="removeConfirm" data-after="removeComment" title="Удалить"><span class="fa fa-trash"></span></a>

<span class="small">
    <?php 
    $class['content'] = ($row['count']['content'] >= 1000) ? 'text-success':'text-warning';
    $class['description'] = ($row['count']['description'] >= 70 && $row['count']['description'] <= 160) ? 'text-success':'text-warning';
    $class['keywords'] = ($row['count']['keywords'] >= 160) ? 'text-success':'text-warning';
    ?>
    <span class="fa fa-file-o <?= $class['content'] ?>" title="Контент, количество символов"></span> <?=$row['count']['content']?>

    <span class="fa fa-file-text-o <?= $class['description'] ?>" title="Описание, количество символов"></span> <?=$row['count']['description']?>

    <span class="fa fa-key <?= $class['keywords'] ?>" title="Ключевики, количество символов"></span> <?=$row['count']['keywords']?>
</span>

<a href="/pages/edit/<?= $row['id'] ?>" title="Редактировать"><span class="fa fa-pencil-square-o"></span></a>
