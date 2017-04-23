<form action="/users/registration" name="login" method="post">
    <div class="form-group">
        <label>Логин</label>
        <div class="input-group">
            <div class="input-group-addon">
                <span class="fa fa-fw fa-user" aria-hidden="true"></span>
            </div>
            <input class="form-control" name="login" type="text" value="<?=$row['login']?>" data-validate="true" data-regexp="[\S]{3,}">
        </div>
    </div>
    <div class="form-group">
        <label>Email</label>
        <div class="input-group">
            <div class="input-group-addon">
                <span class="fa fa-fw fa-at" aria-hidden="true"></span>
            </div>
            <input class="form-control" name="mail" type="mail" value="<?=$row['mail']?>">
        </div>
    </div>
    <div class="form-group">
        <label>Пароль</label>
        <div class="input-group">
            <div class="input-group-addon">
                <span class="fa fa-fw fa-lock" aria-hidden="true"></span>
            </div>
            <input class="form-control" name="password" type="password" data-validate="true" data-regexp="[\S\d]{4,}">
        </div>
    </div>
    <div class="form-group">
        <label>Пароль еще раз</label>
        <div class="input-group">
            <div class="input-group-addon">
                <span class="fa fa-fw fa-lock" aria-hidden="true"></span>
            </div>
            <input class="form-control" name="repeat-password" type="password">
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-primary" type="submit">Создать аккаунт</button>
    </div>
</form>