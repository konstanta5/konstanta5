<form action="" method="post">
    <div class="row">
        <div class="form-group col-xs-12">
            <label>SQL-запросы, каждый в новой строке:</label>
            <textarea class="form-control" name="queries" placeholder="SQL" data-validate="true" data-regexp="."></textarea>
        </div>

        <div class="form-group col-xs-12">
            <button class="btn btn-primary" type="submit">Отправить</button>
        </div>
    </div>
</form>

<div class="m-y-1">
    <?php if(is_array($queries) && count($queries) > 0) :?>
    <table class="table table-striped table-hover">
        <thead>
            <tr><th></th><th>SQL</th><th>Error</th><th>Result</th></tr>
        </thead>
        <tbody>
        <?php foreach($queries as $key => $query) :?>
            <tr>
                <td><?=$key?></td>
                <td><?=$query['sql']?></td>
                <td><?=$query['error']?></td>
                <td><?=$query['result']?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <?php endif?>
</div>