<?php
//$view = new View();

$pager = new Pagination;
$pager->setAll(1000)->setLimit(6)->setCurrentPage(15)->setUrl('pages/page')->pager(2);

?>
<h1><?=$title?></h1>

<?php if(count($rows) > 15):?>
<?=$view->setDir('main')->renderView('_search', array('action'=>'page'));?>
<?php endif ?>

<?php if(in_array(App::$user->status, array('content','moderator','admin'))) :?>
<div class="form-group">
    <a class="btn btn-success btn-sm" href="/news/add"><span class="fa fa-plus"></span> Добавить</a>
</div>
<?php endif?>
<div class="m-t-1"></div>
<?php if(is_array($rows) && count($rows)) : ?>

<?php if(in_array(App::$user->status, array('content','moderator','admin'))) :?>
<?=$view->setDir('news')->renderView('_stat_count', array('count' => $count)) ?>
<?php endif?>

<?=$view->renderView('_list', array('rows'=>$rows))?>

<?php endif?>

<nav aria-label="Page navigation">
  <ul class="pagination">
<!--    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>-->
    <?//=$pager->run()?>
<!--    <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>-->
  </ul>
</nav>