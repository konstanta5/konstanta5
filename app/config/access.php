<?php

/* 
 * Конфигурация доступа к отдельным страницам сайта
 * Страница доступна любому пользователю, если она не описана в этом файле
 * Страница доступна только тем пользователям, чьи id указаны
 */


// comments
$cfg['access']['comments']['index'] = array(1,2,3);
$cfg['access']['comments']['edit'] = array(1);
$cfg['access']['comments']['remove'] = array(1,2,3);
$cfg['access']['comments']['approve'] = array(1,2,3);

// История ЧПУ
$cfg['access']['link']['index'] = array(1,2,3);
$cfg['access']['link']['add'] = array(1,2,3);
$cfg['access']['link']['edit'] = array(1,2,3);

// users
$cfg['access']['users']['index'] = array(1,2,3);

// visits
$cfg['access']['visits']['index'] = array(1,2,3);
$cfg['access']['visits']['all'] = array(1,2,3);