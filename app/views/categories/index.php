<h1><?= $title ?></h1>

<?php if (in_array(App::$user->status, array('admin'))) : ?>
    <div class="form-group">
        <a class="btn btn-success btn-sm" href="/categories/add"><span class="fa fa-plus"></span> Добавить</a>
    </div>
<?php endif; ?>

<?php if (is_array($rows)) : ?>

<ul class="list-group">
    <?php foreach ($rows as $row) :?>
        <li class="list-group-item">
            <?php if (in_array(App::$user->status, array('admin'))) : ?>
            <span class="pull-xs-right">
                <?=$view->renderView('_manager', array('row'=>$row))?>
            </span>
            <?php endif ?>
            <a href="/categories/edit/<?=$row['id']?>" title="Редактировать">
                <span class="fa fa-pencil-square-o"></span>
                <?=$row['name'] ?>
            </a>
        </li>
    <?php endforeach ?>
</ul>
<?php endif?>