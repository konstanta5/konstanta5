<h1><?=$title?></h1>
<form action="/users/passrec" name="passrec" method="post">
    <div class="form-group">
        <label>Логин</label>
        <div class="input-group">
            <div class="input-group-addon">
                <span class="fa fa-fw fa-user" aria-hidden="true"></span>
            </div>
            <input class="form-control" name="login" type="text">
        </div>
    </div>
    <div class="form-group">
        <label>Email</label>
        <div class="input-group">
            <div class="input-group-addon">
                <span class="fa fa-fw fa-at" aria-hidden="true"></span>
            </div>
            <input class="form-control" name="mail" type="mail">
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-primary" type="submit">Восстановить</button>
    </div>
</form>