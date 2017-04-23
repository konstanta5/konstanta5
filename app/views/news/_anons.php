<article class="anons m-b-1 p-b-1">
    <div class="text-xs-center">
        <a href="/news/read/<?= $row['id'] ?>" title="<?= $row['anons'] ?>">
            <img class="img-fluid img-thumbnail" src="/img/news/<?= $row['tizer'] ?>" title="<?= $row['anons'] ?>" alt="<?= $row['anons'] ?>">
            <p class="text-xs-left"><?= $row['anons'] ?></p>
        </a>
    </div>
    <?php if($row['time'] > 0):?>
    <div>
        <time class="small text-muted" datetime="<?= date('Y-m-dTH:i:s', $row['time']) ?>">
            <?= date('d.m.y H:i', $row['time']) ?>
        </time>
    </div>
    <?php endif;?>
    
</article>