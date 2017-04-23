<h4><?=$title?></h4>
<?php if(is_array($row)) : ?>
<?=$view->renderView('_form', array('row'=>$row, 'categories'=>$categories));?>
<?php else : ?>
<p class="table-warning text-warning">Ошибка данных</p>
<?php endif?>