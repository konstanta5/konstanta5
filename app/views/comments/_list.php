<?php
foreach ($comments as $row) :
    // пропускаем не одобренные
    if($row['moderator'] == 0){
        $ip = App::param('ip');
        // если не автор (по IP) и не модератор/админ
        if($row['author_ip'] != $ip && !in_array(App::$user->status, array('moderator','admin'))){
            continue;
        }
    }
    
    // определяем тип комментария
    $card_type = 'card-secondary';
    $card_icon = 'fa-hand-o-right';
    $card_icon = '';
    if ($row['type'] > 0):
        $card_type = ($row['type'] == 1) ? 'card-success' : 'card-danger';
        $card_icon = ($row['type'] == 1) ? 'fa-thumbs-o-up text-success' : 'fa-thumbs-o-down text-danger';
    endif;
    ?>
    <div class="card <?= $card_type ?>">
        <div class="card-header">
            <?php if (in_array(App::$user->status, array('moderator','admin'))) : ?>
                <a class="close text-danger" data-type="ajax" data-after="removeComment" href="/comments/remove/<?=$row['id']?>" title="Удалить">
                    <span aria-hidden="true">&times;</span>
                </a>
                <?php if ($row['moderator'] == 0): ?>
                <a class="text-success pull-xs-right" data-type="ajax" href="/comments/approve/<?=$row['id']?>" title="Одобрить">
                    <span class="fa fa-check" aria-hidden="true"></span>
                </a>
                <?php endif; ?>
            <?php endif; ?>
            <div class="icon small pull-xs-right">
                <span class="fa <?= $card_icon ?>" aria-hidden="true"></span>
            </div>
            <h5 class="card-title">
                <span class="fa fa-user" aria-hidden="true"></span>
                <span class="author"><?= $row['author'] ?></span>
                <small class="text-muted">
                    <span class="fa fa-clock-o" aria-hidden="true"></span>
                    <time><?= date('d.m.y в H:i:s', $row['time']) ?></time>
                </small>
            </h5>
        </div>
        <div class="card-block">
            <p class="card-text"><?= $row['comment'] ?></p>
        </div>
    </div>
<?php endforeach; ?>