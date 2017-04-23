<?php

/* 
 * @autor: fedornabilkin icq: 445537491
 */

class Controller_Main extends Controller {
    
    /**
     * Содержит объект для работы с таблицей rates
     */
    public $model;
    /**
     * Поля из таблицы
     */
    private $fields = array('id','title','description','time');
    
    function __construct() {
        $this->model = new Model_Pages();
        parent::__construct();
        
        //$this->content['aside'] = false;
    }
    
    function action_index() {
        $this->loadConfig('pages');
        
        $this->content['title'] = App::param('site_name');
        $this->content['rows'] = $this->model->setLimit(2)->getRows('', $this->fields, 'ORDER BY time DESC');
        return $this;
    }
    
    function action_list() {
        $this->content['title'] = '';
        return $this;
    }
    
    function action_page() {
        $this->setPageContent();
        return $this;
    }
}