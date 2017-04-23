<form action="/users/login" name="login" method="post">
    <div class="form-group">
        <label>Логин</label>
        <div class="input-group">
            <div class="input-group-addon">
                <span class="fa fa-fw fa-user" aria-hidden="true"></span>
            </div>
            <input class="form-control" name="login" type="text" data-validate="true" data-regexp="[\S]{3,}">
        </div>
    </div>
    <div class="form-group">
        <label>Пароль</label>
        <div class="input-group">
            <div class="input-group-addon">
                <span class="fa fa-fw fa-lock" aria-hidden="true"></span>
            </div>
            <input class="form-control" name="password" type="password">
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-primary" type="submit">Вход</button>
        <a class="pull-xs-right" href="/users/passrec">Восстановить пароль</a>
    </div>
</form>