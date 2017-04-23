<h1><?= $title ?></h1>
<ul>
    <!--<li><a href="/kontakty">Контакты</a></li>-->
</ul>
<div class="row">
    <?php if (is_array($news) && count($news) > 0) : ?>
        <div class="col-sm-6">
            <hr>
            <h2><a href="/">Последние новости</a></h2>
            <ul>
                <?php foreach ($news as $row) : ?>
                    <li><a href="/news/read/<?= $row['id'] ?>"><?= $row['title'] ?></a></li>
                <?php endforeach; ?>
            </ul>        
        </div>  
    <?php endif; ?>
    <?php if (is_array($exchanges) && count($pages) > 0) : ?>
        <div class="col-sm-6">
            <hr>
            <h2><a href="/obmenniki">Обменники</a></h2>
            <ul>
                <?php foreach ($exchanges as $row) : ?>
                    <li><a href="/<?= $row['chpu'] ?>"><?= $row['title'] ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if (is_array($currencies) && count($pages) > 0) : ?>
            <hr>
            <h2><a href="/valuty">Валюты</a></h2>
            <ul>
                <?php foreach ($currencies as $row) : ?>
                    <li><a href="/<?= $row['chpu'] ?>"><?= $row['title'] ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>