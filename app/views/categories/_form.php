<form action="" name="edit" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="form-group col-xs-12 col-sm-6">
            <label>Название</label>
            <input class="form-control" name="name" type="text" value="<?= $row['name'] ?>">
        </div>
        <div class="form-group col-xs-12">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
    </div>
</form>