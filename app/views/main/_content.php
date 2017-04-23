<div class="content"><?=$row['content']?></div>
<?php if(in_array(App::$user->status, array('moderator','admin'))) :?>
<a class="" href="/pages/edit/<?=$row['id']?>" title="Редактировать контент страницы"><span class="fa fa-pencil-square-o"></span></a><br>
<?php endif?>
<div class="m-b-1"></div>