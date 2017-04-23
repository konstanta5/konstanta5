<div class="row small">
    <div class="col-xs-12">
        <div class="btn-group btn-group-sm">
            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseSql" aria-expanded="false" aria-controls="collapseSql">
                SQL: <span class="tag-pill <?=(count($develop['sql_queries']) > 15) ? (count($develop['sql_queries']) >= 25 ? 'bg-danger':'tag-warning') : 'bg-success'?>"><?= count($develop['sql_queries']) ?></span>
            </a>
            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseRoute" aria-expanded="false" aria-controls="collapseRoute">
                Route: <span class="tag-pill"><?= implode('/', $develop['routes']) ?></span>
            </a>
            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseClass" aria-expanded="false" aria-controls="collapseClass">
                Class: <span class="tag-pill bg-success"><?= count($develop['class']) ?></span>
            </a>
            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseViews" aria-expanded="false" aria-controls="collapseViews">
                Views: <span class="tag-pill bg-success"><?= count($develop['views']) ?></span>
            </a>
            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseModels" aria-expanded="false" aria-controls="collapseModels">
                Models: <span class="tag-pill bg-success"><?= count($develop['models']) ?></span>
            </a>
            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseServer" aria-expanded="false" aria-controls="collapseServer">
                $_SERVER
            </a>
            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseTimes" aria-expanded="false" aria-controls="collapseTimes">
                Time: <span class="tag-pill <?=($develop['time']['start'] > 0.1) ? ($develop['time']['start'] >= 1 ? 'bg-danger':'tag-warning') : 'bg-success'?>"><?= $develop['time']['start'] ?></span>
            </a>
        </div>
    </div>
    <div class="collapse col-xs-12" id="collapseSql">
        <div class="card card-block">
            <?php if ($develop['sql_queries']): ?>
            <table class="table table-sm table-striped table-bordered">
                <thead>
                    <tr><th>#</th><th>Time</th><th>Query</th></tr>
                </thead>
                <tbody>
                <?php
                foreach ($develop['sql_queries'] as $row) :
                    $all_time += $row['time'];
                    $tr = ($row['time'] > 0.05) ? 'table-warning': '';
                    $tr = ($row['time'] > 0.2) ? 'table-danger': $tr;
                ?>
                    <tr class="<?=$tr?>"><td><?= $row['num'] ?></td><td><?= round($row['time'],5) ?></td><td><?= $row['sql'] ?></td></tr>
                <?php endforeach; ?>
                    <tr><td>Общее время:</td><td><?= round($all_time,5) ?></td><td></td></tr>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="collapse col-xs-12" id="collapseRoute">
        <div class="card card-block">
            <div><strong>URI:</strong> <span class="small text-muted"><?= getenv('REQUEST_URI'); ?></span></div>
            <div><strong>Controller:</strong> <span class="small text-muted">/app/controllers/controller_<?= $develop['routes'][1] ?>.php</span></div>
            <div><strong>View:</strong> <span class="small text-muted">/app/views/<?= $develop['routes'][1] ?>/<?= $develop['routes'][2] ?>.php</span></div>
        </div>
    </div>
    <div class="collapse col-xs-12" id="collapseClass">
        <div class="card card-block">
            <?php 
                $list = array();
                foreach ($develop['class'] as $row) {
                    $list[$row] += 1;
                }
            ?>
            <?php foreach ($list as $view => $num) :?>
            <div>
                <span class="small text-muted"><?= $view ?></span>
                <span class="tag-pill tag-info"><?= $num ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="collapse col-xs-12" id="collapseViews">
        <div class="card card-block">
            <?php 
                $list = array();
                foreach ($develop['views'] as $row) {
                    $list[$row] += 1;
                }
            ?>
            <?php foreach ($list as $view => $num) :?>
            <div>
                <span class="small text-muted"><?= $view ?></span>
                <span class="tag-pill tag-info"><?= $num ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="collapse col-xs-12" id="collapseModels">
        <div class="card card-block">
            <?php 
                $list = array();
                foreach ($develop['models'] as $row) {
                    $list[$row] += 1;
                }
            ?>
            <?php foreach ($list as $model => $num) :?>
            <div>
                <span class="small text-muted">/app/models/<?= $model ?>.php</span>
                <span class="tag-pill tag-info"><?= $num ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="collapse col-xs-12" id="collapseTimes">
        <div class="card card-block">
            <?php if ($develop['time']): ?>
            <table class="table table-sm table-striped table-bordered">
                <thead>
                    <tr><th>#</th><th>Time</th><th>Block</th></tr>
                </thead>
                <tbody>
                <?php
                foreach ($develop['time'] as $key => $time) : 
                    $num++;
//                    $tr = (($time - $last_time) > 0.05) ? 'table-warning': '';
//                    $tr = (($time - $last_time) > 0.5) ? 'table-danger': $tr;
                    $tr = ($time > 0.05) ? 'table-warning': '';
                    $tr = ($time > 0.5) ? 'table-danger': $tr;
                ?>
                    <tr class="<?=$tr?>"><td scope="row"><?= $num ?></td><td><?= $time ?></td><td><?= $key ?></td></tr>
                <?php
                    $last_time = $time;
                endforeach;
                ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="collapse col-xs-12" id="collapseServer">
        <div class="card card-block">
            <table class="table table-sm table-striped table-bordered">
                <thead>
                    <tr><th>#</th><th>Key</th><th>Value</th></tr>
                </thead>
                <tbody>
                <?php
                foreach ($_SERVER as $key => $val) : $num++;?>
                    <tr><td scope="row"><?= $num ?></td><td><?= $key ?></td><td><?= $val ?></td></tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>

</div>