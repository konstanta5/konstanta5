<h1><?=$title?></h1>

<?php if(is_array($rows) && count($rows) > 0):?>
<table class="table table-hover table-striped">
    <thead>
        <tr><th>id</th><th>Логин</th><th>Статус</th><th>Регистрация</th><th></th></tr>
    </thead>
    <tbody>
        <?php foreach($rows as $row):?>
        <tr>
            <td><?=$row['id']?></td>
            <td>
                <?=$row['login']?><br>
                <span class="small text-muted"><?=$row['mail']?></span>
            </td>
            <td><?=$row['status']?></td>
            <td>
                <?=date('d.m.y в H:i:s', $row['last_login'])?><br>
                <span class="text-muted"><?=date('d.m.y', $row['time'])?></span>
            </td>
            <td>
                <?php if(in_array(App::$user->status, array('moderator','admin'))):?>
                <span class="mngr">
                    <a href="/users/status/<?=$row['id']?>" data-type="ajax" data-status="content" title="Сделать контент-менеджером">
                        <span class="fa fa-pencil-square-o text-success" aria-hidden="true"></span>
                    </a>
                    <a href="/users/status/<?=$row['id']?>" data-type="ajax" data-status="moderator" title="Сделать модератором">
                        <span class="fa fa-cogs" aria-hidden="true"></span>
                    </a>
                    <a href="/users/status/<?=$row['id']?>" data-type="ajax" data-status="admin" title="Сделать администратором">
                        <span class="fa fa-male text-warning" aria-hidden="true"></span>
                    </a>
                    <a href="/users/status/<?=$row['id']?>" data-type="ajax" data-status="" title="Удалить статус">
                        <span class="fa fa-ban text-danger" aria-hidden="true"></span>
                    </a>
                </span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php else:?>
<div class="alert alert-warning">Пользователи не найдены</div>
<?php endif; ?>
