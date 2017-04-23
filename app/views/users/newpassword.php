<h1><?=$title?></h1>
<form action="" name="passrec" method="post">
    <div class="form-group">
        <label>Новый пароль</label>
        <div class="input-group">
            <div class="input-group-addon">
                <span class="fa fa-fw fa-lock" aria-hidden="true"></span>
            </div>
            <input class="form-control" name="password" type="password">
        </div>
    </div>
    <div class="form-group">
        <label>Новый пароль еще раз</label>
        <div class="input-group">
            <div class="input-group-addon">
                <span class="fa fa-fw fa-lock" aria-hidden="true"></span>
            </div>
            <input class="form-control" name="repeat_password" type="password">
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-primary" type="submit">Сохранить</button>
    </div>
</form>