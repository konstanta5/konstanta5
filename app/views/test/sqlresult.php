<?php if($query['error']):?>
<div class="alert alert-danger"><?=$query['error']?></div>
<?php endif?>

<form action="" method="post">
    <div class="row">
        <div class="form-group col-xs-12">
            <label>SQL-запрос:</label>
            <textarea class="form-control" name="query" placeholder="SQl" data-validate="true" data-regexp="."><?=$query['sql']?></textarea>
            <div>
                <span class="sql-example pointer dashed" data-sql="SELECT * FROM table WHERE id = '1'">SELECT</span>
                <span class="sql-example pointer dashed" data-sql="SELECT * FROM table LEFT JOIN table2 ON table.field = table2.field WHERE id = '1'">SELECT JOIN</span>
            </div>
        </div>

        <div class="form-group col-xs-12">
            <button class="btn btn-primary" type="submit">Отправить</button>
            <label><input type="checkbox" name="explain" value="1" checked> EXPLAIN</label>
        </div>
    </div>
</form>

<div class="m-y-1">
    <?php if(is_array($explain) && count($explain) > 0):?>
    <?=$view->renderView('_rows', array('rows'=>$explain))?>
    <?php endif?>
    
    <div class="m-b-1"></div>
    time: <?=$query['time']?> rows: <?=count($query['result'])?>
    <?php if(is_array($query['result']) && count($query['result']) > 0) :?>
    <?=$view->renderView('_rows', array('rows'=>$query['result']))?>
    <?php endif?>
</div>