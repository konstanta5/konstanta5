<?php

/* 
 * slove4ki.ru V3.0
 * autor: fedornabilkin
 * icq: 445537491
 */

class Controller_About extends Controller {

//    function __construct() {
//        // передаем в конструктор имя таблицы
////        $this->model = new Model_About(Route::$static_controller);
//        // перегружаем конструктор контроллера ядра
////        parent::__construct();
//    }
    
    function action_index() {
//        $this->data = $this->model->get_data();
        $this->view_file = 'about_view.php';
//         устанавливаем файл шаблона, если нужен не основной
        //$this->tpl_file = 'template_view';
        return $this;
    }
    
    function action_add($data=FALSE){
        $this->data = $this->model->set_data($data);
        $this->view_file = 'status_view.php';
        return $this;
    }
}