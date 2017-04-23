<form action="" name="edit" method="post">
    <div class="row">
        <div class="form-group col-xs-12 col-sm-6">
            <label>Название</label>
            <input class="form-control" name="title" type="text" value="<?= $row['title'] ?>" >
        </div>
        <div class="form-group col-xs-12 col-sm-6">
            <span class="pull-xs-right fa fa-question-circle" data-toggle="tooltip" title="info - обычная (главная, контакты). Для простых не указывать."></span>
            <label>Тип страницы</label>
            <input class="form-control" name="type" type="text" value="<?= $row['type'] ?>" >
        </div>
        <div class="form-group col-xs-12">
            <label>Контент</label>
            <textarea class="form-control" name="content" id="content"><?= $row['content'] ?></textarea>
        </div>
        <div class="form-group col-xs-12 col-sm-6">
            <label>Описание</label>
            <textarea class="form-control" name="description"><?= $row['description'] ?></textarea>
        </div>
        <div class="form-group col-xs-12 col-sm-6">
            <label>Ключевые слова</label>
            <input class="form-control" name="keywords" type="text" value="<?= $row['keywords'] ?>" >
        </div>
        <div class="form-group col-xs-12">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
    </div>
</form>