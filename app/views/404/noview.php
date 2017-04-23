<?php
// !!!!!!!!!!!
// Дополнительную информацию не добавлять, может отображаться в разных частях страницы
// !!!!!!!!!!!
    
    if(!in_array(App::$user->status, array('moderator','admin'))){
        $path = '';
    }
?>
<div class="alert alert-warning">Не найден файл шаблона <?=$path?></div>