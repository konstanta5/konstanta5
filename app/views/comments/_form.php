<div class="m-b-1">
    <button class="btn btn-success pull-xs-right" type="button" data-toggle="collapse" data-target="#collapseAddComments" aria-expanded="false" aria-controls="collapseAddComments">
        <span class="fa fa-plus" aria-hidden="true"></span>
        Добавить отзыв
    </button>
    <div class="clearfix"></div>
</div>
<div class="collapse" id="collapseAddComments">
    <form action="/comments/add" name="add" method="post">
        <input name="entity" type="hidden" value="<?= Route::$controller ?>" >
        <input name="entity_id" type="hidden" value="<?= $row['id'] ?>" >
        <div class="row">
            <div class="form-group col-xs-12 col-sm-4">
                <label>Имя</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="fa fa-male" aria-hidden="true"></span>
                    </div>
                    <input class="form-control" name="author" type="text" value="<?= $row['author'] ?>" placeholder="Имя">
                </div>
            </div>
            
            <div class="form-group col-xs-12 col-sm-8">
                <label>Тип отзыва</label>
                <div class="review-type">
                    <label title="Нейтральный"><input type="radio" name="type" value="0" checked> <span class="fa fa-hand-o-right" aria-hidden="true"></span></label>
                    <label title="Положительный"><input type="radio" name="type" value="1"> <span class="fa fa-thumbs-o-up text-success" aria-hidden="true"></span></label>
                    <label title="Отрицательный"><input type="radio" name="type" value="2"> <span class="fa fa-thumbs-o-down text-danger" aria-hidden="true"></span></label>
                </div>
            </div>
            
            <div class="form-group col-xs-12">
                <label>Отзыв</label>
                <textarea class="form-control" name="comment" placeholder="Текст отзыва"><?= $row['comment'] ?></textarea>
            </div>
            
            <div class="form-group col-xs-12">
                <button class="btn btn-primary" type="submit">Отправить</button>
            </div>
        </div>
    </form>
</div>