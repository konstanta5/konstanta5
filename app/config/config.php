<?php

//System Configurations

date_default_timezone_set('Europe/Moscow');
//$script_tz = date_default_timezone_get();
ini_set('display_errors', 0);
error_reporting(0);

$cfg = array(
    
    //'ip' => $_SERVER['HTTP_X_REAL_IP'],
    'ip' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
    'referer' => $_SERVER['HTTP_REFERER'],
    'home_url' => 'bipca.ru',
    'http_home_url' => 'http://bipca.ru',
    
    // meta
    'site_name' => 'Биржа промо-кодов',
    'description' => 'Продажа и покупка промо-кодов акций производителей. Выставляй коды на продажу и не жди пока кто-то напишет в ЛС.',
    'keywords' => 'продажа покупка промо-кодов акций',
    
    // support
    'support_mail' => 'no-reply@bipca.ru',
    
    // время жизни секретного ключа для восстановления пароля
    'secretKeyExpire' => 3600,
    
    // группы управления
    'content' => array('content', 'moderator','admin'),
    'moderator' => array('moderator','admin'),
    'admin' => array('admin'),
    
    // логины разработчиков
    'develops' => array('fedornabilkin', 'admin'),
    
    
);

// chpu
//$cfg['chpu'][] = array('chpu'=>'obmenniki', 'ctrl'=>'exchange', 'action'=>'');
//$cfg['chpu'][] = array('chpu'=>'kursy-obmena', 'ctrl'=>'rates', 'action'=>'');
//$cfg['chpu'][] = array('chpu'=>'valuty', 'ctrl'=>'currency', 'action'=>'');
//$cfg['chpu'][] = array('chpu'=>'kontakty', 'ctrl'=>'contacts', 'action'=>'');

//$cfg['chpu'][] = array('chpu'=>'privacy-terms', 'ctrl'=>'main', 'action'=>'page');

$cfg['chpu'][] = array('chpu'=>'sitemap.xml', 'ctrl'=>'map', 'action'=>'sitemap');
$cfg['chpu'][] = array('chpu'=>'robots.txt', 'ctrl'=>'map', 'action'=>'robots');
