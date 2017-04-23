<h1><?= $title ?></h1>
<div class="row m-b-3">
    <div class="col-xs-5 col-lg-3">
        <img class="avatar img-rounded img-thumbnail" src="/img/avatar/<?= App::$user->avatar ?>" alt="" height="100" width="100">
    </div>
    <div class="col-xs-7 col-lg-9">
        <div class="upload upload_avatar form-group">
            <label class="file">
                <input id="file" name="avatar" type="file">
                <span class="file-custom"></span>
            </label>
            <div>
                <progress class="hidden-xs-up progress progress-striped progress-animated" max="100" value="1"></progress>
            </div>
            <div class="resp"></div>
        </div>
    </div>
</div>
<form action="" name="profile" method="post">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Данные</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active" id="home" role="tabpanel">
            <div class="form-group">
                <label>Текущий пароль</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="fa fa-fw fa-lock" aria-hidden="true"></span>
                    </div>
                    <input class="form-control" name="password" type="password">
                </div>
            </div>
            <div class="form-group">
                <label>Новый пароль</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="fa fa-fw fa-lock" aria-hidden="true"></span>
                    </div>
                    <input class="form-control" name="newpassword" type="password">
                </div>
            </div>
            <div class="form-group">
                <label>Новый пароль еще раз</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="fa fa-fw fa-lock" aria-hidden="true"></span>
                    </div>
                    <input class="form-control" name="newpassword_repeat" type="password">
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="fa fa-fw fa-at" aria-hidden="true"></span>
                    </div>
                    <input class="form-control" name="mail" type="mail" value="<?= $row['mail'] ?>">
                    <div class="input-group-addon">
                        <span class="fa fa-fw fa-check text-success" title="Подтвержден" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-primary" type="submit">Сохранить</button>
    </div>
</form>