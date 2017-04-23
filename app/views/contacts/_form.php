<form action="/kontakty" method="post">
    <div class="row">
        <div class="form-group col-xs-12 col-sm-6">
            <label>Ваше имя</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <span class="fa fa-male" aria-hidden="true"></span>
                </div>
                <input class="form-control" name="name" value="<?= $row['name'] ?>" placeholder="Имя" data-validate="true">
            </div>
        </div>

        <div class="form-group col-xs-12 col-sm-6">
            <span class="pull-xs-right fa fa-question-circle" data-toggle="tooltip" title="Оставьте электронный адрес, если хотите получить ответ."></span>
            <label>Ваш e-mail</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <span class="fa fa-at" aria-hidden="true"></span>
                </div>
                <input class="form-control" name="email" value="<?= $row['email'] ?>" placeholder="E-mail" data-validate="true">
            </div>
        </div>

        <div class="form-group col-xs-12">
            <label>Текст сообщения</label>
            <textarea class="form-control" name="message" placeholder="Добавьте сообщение" data-validate="true" data-regexp="[\S]{15,}"><?= $row['message'] ?></textarea>
        </div>

        <div class="form-group col-xs-12">
            <button class="btn btn-success" type="submit"><span class="fa fa-envelope-o" aria-hidden="true"></span> Отправить</button>
        </div>
    </div>
</form>