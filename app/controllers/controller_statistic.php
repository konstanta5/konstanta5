<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */

class Controller_Statistic extends Controller {
    
    /**
     * Содержит объект для работы с таблицей
     */
    public $model;
    public $entity;
    /**
     * Временные метки в секундах
     */
    public $curr_time;
    public $today;
    public $yesterday;
    public $month;
    public $lastmonth;
    
    private $news_fields = array('id','anons','tizer','title','content','description','keywords','time');
    private $wh = array();
            
    function __construct() {
        $this->curr_time = time();
        $this->today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $this->yesterday = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
        $this->month = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $this->lastmonth = mktime(0, 0, 0, date("m")-1, date("d"), date("Y"));
        
        parent::__construct();
        
        
        $this->entity = ucfirst(Route::$routes[3]);
        if(!Route::$routes[3]){
            $this->entity = ucfirst('news');
        }
        $model = 'Model_' .$this->entity;
        // если нет файла, то выдаем 404
        if (!file_exists(APP_DIR . "/models/" . strtolower($model) . '.php')) {
            $this->errorPage();
            $this->action();
            return $this;
        }
        $this->model = new $model();
        $this->view_dir = (Route::$routes[3]) ? Route::$routes[3]: 'news';
        $this->view_file = 'index';
        $this->checkLogin();
    }
    
    
    public function action_index() {
//        $this->content['rows'] = $this->model->setExchange($this->exch_id)->today();
//        $this->content['count'] = count($this->content['rows']);
        $this->content['title'] = 'Статистика';
        return $this;
    }
    
    
    public function action_today() {
        $this->content['title'] = 'За сегодня';
        $this->wh += array('time >'=>$this->today);
        $method = 'get' .$this->entity;
        $this->$method();
        return $this;
    }
    
    
    public function action_yesterday() {
        $this->content['title'] = 'За вчера';
        $this->wh += array('time >'=>$this->yesterday,'time <'=>$this->today);
        $method = 'get' .$this->entity;
        $this->$method();
        return $this;
    }
    
    
    public function action_month() {
        $this->content['title'] = 'За текущий месяц';
        $this->wh += array('time >'=>$this->month);
        $method = 'get' .$this->entity;
        $this->$method();
        return $this;
    }
    
    
    public function action_lastmonth() {
        $this->content['title'] = 'За прошлый месяц';
        $this->wh += array('time >'=>$this->lastmonth, 'time <'=>$this->month);
        $method = 'get' .$this->entity;
        $this->$method();
        return $this;
    }
    
    
    /**
     * Проверка на существование логина
     * @return object $this
     */
    public function checkLogin() {
        if(!Route::$routes[4] or !App::$user->id){
            return;
        }
        $uid = App::$user->findByLogin(Route::$routes[4])['id'];
        if($uid > 0){
            $this->wh += array('author'=>$uid);
            $this->content['author'] = Route::$routes[4];
        }
        return;
    }
    
    
    /**
     * Получает количество новостей с учетом фильтра
     * @return object $this
     */
    public function getNews() {
        $this->wh += array('hide !'=>1);
        $this->content['rows'] = $this->model->getNewsRows($this->wh, $this->news_fields, 'ORDER BY id DESC');
        $this->content['count']['stat']['all'] = count($this->content['rows']);
    }
    
    
    /**
     * Получает количество новостей за сегодня
     * @return object $this
     */
    public function getNewsToday() {
        $this->wh += array('hide !'=>1);
        $this->content['rows'] = $this->model->getNewsRows($this->wh, $this->news_fields, 'ORDER BY id DESC');
        $this->content['count']['stat']['all'] = count($this->content['rows']);
    }
    
    
    /**
     * Получает количество новостей за вчера
     * @return object $this
     */
    public function getNewsYesterday() {
        $this->wh += array('hide !'=>1);
        $this->content['rows'] = $this->model->getNewsRows($this->wh, $this->news_fields, 'ORDER BY id DESC');
        $this->content['count']['stat']['all'] = count($this->content['rows']);
    }
    
    
    /**
     * Получает количество новостей за этот месяц
     * @return object $this
     */
    public function getNewsMonth() {
        $this->wh += array('hide !'=>1);
        $this->content['rows'] = $this->model->getNewsRows($this->wh, $this->news_fields, 'ORDER BY id DESC');
        $this->content['count']['stat']['all'] = count($this->content['rows']);
    }
    
    
    /**
     * Получает количество новостей за прошлый месяц
     * @return object $this
     */
    public function getNewsLastmonth() {
        $this->wh += array('hide !'=>1);
        $this->content['rows'] = $this->model->getNewsRows($this->wh, $this->news_fields, 'ORDER BY id DESC');
        $this->content['count']['stat']['all'] = count($this->content['rows']);
    }
    
    
}