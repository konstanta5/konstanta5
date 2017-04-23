<article class="anons m-b-1 p-b-1">
    <div class="text-xs-center">
        <a href="/pages/<?= $row['id'] ?>" title="<?= $row['title'] ?>">
            <p class="text-xs-left"><?= $row['title'] ?></p>
        </a>
        
    </div>
    <?= $row['description'] ?>
    <?php if($row['time'] > 0):?>
    <div>
        <time class="small text-muted" datetime="<?= date('Y-m-dTH:i:s', $row['time']) ?>">
            <?= date('d.m.y H:i', $row['time']) ?>
        </time>
    </div>
    <?php endif;?>
    
</article>