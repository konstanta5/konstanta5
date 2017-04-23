<form action="" name="edit" method="post" enctype="multipart/form-data">
    <div class="row">
        <?php if (in_array(App::$user->status, array('content', 'moderator', 'admin'))) : ?>
            <div class="col-xs-12">
                <div class="form-group">
                    <label>Анонс</label>
                    <input class="form-control" name="anons" type="text" value="<?= $row['anons'] ?>" >
                </div>
            </div>
            <div class="form-group col-xs-12">
                <?php if ($row['id']): ?>
                <div class="form-group tizer-label m-r-1 pull-sm-left">
                    <label>Тизер анонса (255x165)</label>
                    <div>
                        <img class="img-fluid img-thumbnail" src="/img/news/<?= $row['tizer'] ?>" alt="" >
                    </div>
                </div>
                <div class="form-group">
                    <label>Загрузить изображение</label>
                    <div class="upload">
                        <label class="custom-file">
                            <input class="custom-file-input" id="file" name="file" type="file" data-url="/news/uptizer/<?= $row['id'] ?>" data-after="aTizerUpload">
                            <span class="custom-file-control">Выбрать файл</span>
                        </label>
                        <span><progress class="hidden-xs-up progress progress-striped progress-animated" max="100" value="1" role="progressbar"></progress></span>
                        <div class="resp"></div>
                    </div>
                </div>
                <?php else: ?>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Тизер анонса (255x165)</label>
                            <label class="custom-file">
                                <input class="custom-file-input" id="file" name="file" type="file">
                                <span class="custom-file-control">Выбрать файл</span>
                            </label>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="form-group col-xs-12">
                <label>Заголовок</label>
                <input class="form-control" name="title" type="text" value="<?= $row['title'] ?>" >
            </div>
            <div class="form-group">
                <label>Контент</label>
                <textarea class="form-control" name="content" id="content"><?= $row['content'] ?></textarea>
            </div>
            <div class="form-group col-xs-12 col-sm-6">
                <label>Описание</label>
                <textarea class="form-control" name="description"><?= $row['description'] ?></textarea>
            </div>
            <div class="form-group col-xs-12 col-sm-6">
                <label>Ключевые слова</label> <a href="https://wordstat.yandex.ru/" target="_blank">wordstat</a>
                <input class="form-control" name="keywords" type="text" value="<?= $row['keywords'] ?>" >
            </div>
            <div class="form-group col-xs-12 col-sm-6">
                <label>Категория</label>
                <select class="form-control" name="category">
                    <?php foreach($categories as $val):?>
                    <option value="<?= $val['id'] ?>" <?=($val['id'] == $row['category']) ? 'selected': '' ?>><?= $val['name'] ?></option>
                    <?php endforeach;?>
                </select>
            </div>
        <?php endif; ?>
        <div class="form-group col-xs-12">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
    </div>
</form>