<?php

    header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
    header("Status: 404 Not Found");
    
    $current_url = App::param('http_home_url') . Route::$uri;
    $current_chpu = substr(Route::$uri, 1);
    
    $row['message'] = 'Здравствуйте! Мной обнаружена несуществующая страница по адресу ' .$current_url;
?>
<h1><?=$title?></h1>
<div class="alert alert-danger"><?=$msg?></div>

<?php if (in_array(App::$user->status, array('moderator', 'admin'))) : ?>
<h5>Добавить новый ЧПУ для 301 перенаправления</h5>
<?=$view->setDir('link')->renderView('_form', array('row'=>array('old'=> $current_chpu )) )?>
<?php endif; ?>

<div class="m-y-1">
    <p>К сожалению, мы не можем найти запрашиваемую вами страницу. Возможно она была удалена, изменена или не существовала вовсе.
        Если вы уверены, что данный адрес корректный или попали на эту страницу с другого сайта,
        пожалуйста сообщите в <span class="text-info dashed pointer" data-toggle="collapse" data-target="#collapseContacts" aria-expanded="false" aria-controls="collapseContacts">поддержку</span>.</p>
    
    <div class="collapse" id="collapseContacts">
    <?=$view->setDir('contacts')->renderView('_form', array('row' => $row)) ?>
    </div>
    
</div>
