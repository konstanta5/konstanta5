<?php

/*
 * @autor fedornabilkin icq: 445537491
 */

class Controller {

//    public $model;
    
    /**
     * Объект для работы с видами
     */
    public $view;
    
    /**
     * Название дирректории файлов видов.
     */
    public $view_dir;
    
    /**
     * Название файла вида.
     */
    public $view_file;
    
    /**
     * Название основного шаблона
     */
    public $layout_file = 'main';
    
    public $log;
    
    /**
     * array parts uri (/pages/read/chpu)
     */
//    public $routes;
    
    /**
     * object session
     */
    public $session;
    
    /**
     * object access
     */
    public $access;
    
    /**
     * Массив данных для рендеринга основного шаблона
     */
    public $data;
    
    /**
     * Массив данных для рендеринга основного вида
     */
    public $content;
    
    /**
     * array $_GET
     */
    private $get;
    
    /**
     * array $_POST
     */
    private $post;
    
            
    function __construct() {
//        if(in_array(App::$user->login, App::param('develops'))){
//            print_r(Route::$uri);
//        }
        Timer::start('Core');
        Timer::start(get_class($this));
        $this->log = new Logs();
        // view object
        if(App::param('content_type') != 'json'){
            $this->view = new View();
            $this->view->dir = $this->view_dir = Route::$controller;
            $this->view_file = Route::$routes[2];
        }
        else{
            $this->content['error'] = false;
        }
        
        
        App::$user = new Model_Users();
        
        // session
        $this->session = new Session;

        // user
        $user = $this->session->get('user');
        if($user){
            App::$user->setParams($user);
            App::$user->edit(App::$user->id, array('last_login'=>time()));
        }
        // access получает массив из app/config/access.php
        $this->access = new Access(App::param('access'), App::$user->id);
        $this->accessPage();
        
        $this->content['aside'] = true;
        
        // GET POST
        if($_GET){ $this->setGet($_GET); }
        if($_POST){ $this->setPost($_POST); }
        
    }

    /**
     * Собирает и отображает страницу с данными
     * @return echo html page
     */
    function action() {
        Timer::stop(get_class($this));
        Timer::start('View');
        // ajax
        if(App::param('content_type') == 'json'){
            $this->setAlert();
            
            if(in_array(App::$user->login, App::param('develops'))){
                $this->setDevelop();
                $this->content['develop'] = $this->data['develop'];
            }
            exit(json_encode($this->content));
        }
        
        // alert nav breadcrumbs aside metatags
        Timer::start('View_blocks');
        $this->setAlert()->setNav()->setAside()->setFooter()->setBreadcrumbs()->setMetaTags();
        
        Timer::stop('View_blocks',7);
        
        Timer::start('View_data');
                
        // view dir ---
        $this->view->dir = $this->view_dir;
        $this->data['content'] = $this->view->renderView($this->view_file, $this->content); // Страница
        Timer::stop('View_data',7);
        
        Timer::stop('View',7);
        Timer::stop('Core',7);
        
        if(in_array(App::$user->login, App::param('develops'))){
            $this->setDevelop();
            $this->data['develop'] = $this->view->renderLayout('develop', array('develop'=>$this->data['develop'])); // Разработка
        }
        echo $this->view->renderLayout($this->layout_file, $this->data); // Шаблон
        
    }


    function action_index() {}

    
    /**
     * Загружает файл конфига из app/config
     * @param string $name Название файла без .php
     * @return object $this
     */
    public function loadConfig($name) {
        Timer::start('loadConfig');
        
        $lcfg = new loadConfig($name);
        $cfg = $lcfg->load();
        
        if(is_array($cfg)){
            App::setParam($cfg);
        }
        
        Timer::stop('loadConfig',7);
        return $this;
    }
    
    
    /**
     * Отдает на рендеринг alert, если есть текст
     * @return object $this
     */
    public function setAlert() {
        Timer::start('setAlert');
        if(!$this->content['alert']){
            $this->content['alert'] = $this->session->getFlash('alert');
        }
        if($this->content['alert']){
            $alert['alert']['msg'] = $this->content['alert'];
            $alert['alert']['type'] = ($this->session->getFlash('error')) ? 'danger': ($this->content['type'] ? $this->content['type'] : 'success');
            if(App::param('content_type') != 'json'){
                $this->data['alert'] = $this->view->renderLayout('alert', $alert);
            }
            else{
                $this->content['alert'] = array('msg'=>$alert['alert']['msg'], 'type'=>$alert['alert']['type']);
            }
        }
        Timer::stop('setAlert',7);
        return $this;
    }

    
    /**
     * Отдает на рендеринг nav
     * @return object $this
     */
    public function setNav() {
        Timer::start('setNav');
        $this->data['nav'] = $this->view->renderLayout('nav');
        Timer::stop('setNav',7);
        return $this;
    }

    
    /**
     * Отдает на рендеринг breadcrumbs
     * @return object $this
     */
    public function setBreadcrumbs() {
        Timer::start('setBreadcrumbs');
        
        if($this->content['breadcrumbs']){
            if($this->content['row']['title']){
                $this->content['breadcrumbs'][] = array('label'=>$this->content['row']['title']);
            }
            $bc = new Breadcrumbs(array('label' => App::param('site_name'), 'url' => '/'));
            
            $breadcrumbs = $bc->setLinks($this->content['breadcrumbs'])->run();
            $this->data['breadcrumbs'] = $this->view->renderLayout('breadcrumbs', array('breadcrumbs'=>$breadcrumbs));
        }
        Timer::stop('setBreadcrumbs',7);
        return $this;
    }

    
    /**
     * Получает контент для страницы, если есть
     * @return void
     */
    public function setPageContent() {
        Timer::start('setPageContent');
        // если контент не найден, пробуем поискать страницу по ЧПУ
        $model = new Model_Pages('pages');
        $parts = explode('/', Route::$uri);
        $parts[1] = ($parts[1]) ? $parts[1]: 'main';
        if($parts[2]){
            return $this;
        }
        $fields = array('id','title','content','description','keywords');
        $row = $model->getPageByChpu($this->sanitization($parts[1]), $fields);
        
        if($row){
            $this->content['row'] = $row;
            $this->content['title'] = ($this->content['row']['title']) ? $this->content['row']['title'] : $this->content['title'] ;
            
            $bb = new BBCode($this->content['row']['content']);
            $this->content['row']['content'] = $bb->getText();
            $bb->setText($this->content['row']['content_after']);
            $this->content['row']['content_after'] = $bb->getText();
        }
        Timer::stop('setPageContent',7);
    }

    
    /**
     * Отдает на рендеринг aside
     * @return object $this
     */
    public function setAside() {
        if($this->content['aside'] === false){
            $this->data['aside'] = false;
            return $this;
        }
        Timer::start('setAside');
        
        $model = new Model_Pages();
        $fields = array('id','title');
        $wh = array('id !'=>Route::$routes[3]);
        $wh = '';
        $aside['lastpages'] = $model->setLimit(4)
                ->getRows($wh, $fields, 'ORDER BY time DESC');
        $this->data['aside'] = $this->view->renderLayout('aside', array('aside'=>$aside));

        Timer::stop('setAside',7);
        return $this;
    }

    
    /**
     * Отдает на рендеринг aside
     * @return object $this
     */
    public function setFooter() {
        Timer::start('setFooter');
        
        $fields = array('id','login');
        $footer['users_online'] = App::$user
                ->getRows(array('last_login >'=>(time() - 60*5)), $fields, 'ORDER BY last_login DESC');
        $this->data['footer'] = $this->view->renderLayout('footer', array('footer'=>$footer));

        unset($fields, $footer);
        Timer::stop('setFooter',7);
        return $this;
    }

    
    /**
     * Устанавливает значения метатегов
     * @return object $this
     */
    public function setMetaTags() {
        Timer::start('setMetaTags');
        $this->data['title'] = $this->content['title'];
        $this->data['description'] = $this->content['row']['description'];
        $this->data['keywords'] = $this->content['row']['keywords'];
        
        if(!$this->content['row']['description']){
            $this->data['description'] = App::param('description');
        }
        if(!$this->content['row']['keywords']){
            $this->data['keywords'] = App::param('keywords');
        }
        
        if(count($this->content['ogp'])){
            foreach ($this->content['ogp'] as $prop => $content) {
                $this->data['opengraph'] .= '<meta property="'.$prop.'" content="'.$content.'">';
            }
            unset($this->content['ogp'], $prop, $content);
        }
        
        Timer::stop('setMetaTags',7);
        return $this;
    }

    
    /**
     * Устанавливает значения метатегов
     * @return object $this
     */
    public function setDevelop() {
        $this->data['develop']['sql_queries'] = Mysql::$query_list;
        $this->data['develop']['routes'] = Route::$routes;
        $this->data['develop']['class'] = Load::$classes;
        $this->data['develop']['views'] = View::$views;
        $this->data['develop']['models'] = Model::$child_models;
        $this->data['develop']['post'] = $this->getPost();
        $this->data['develop']['get'] = $this->getGet();
        $this->data['develop']['time'] = Timer::getResult();
        return $this;
    }

    
    /**
     * Отдает на рендеринг страницу ошибки
     * @param string $msg Сообщение об ошибке
     * @param string $title Заголовок
     * @return void
     */
    public function errorPage($msg='404 страница не найдена.', $title='404 страница не найдена') {
        $this->view_dir = '404';
        $this->view_file = 'error';
        $this->content['title'] = $title;
        $this->content['msg'] = $msg;
        $this->content['error'] = true;
    }
    
    
    /**
     * Проверяет доступ к страницам сайта
     */
    public function accessPage() {
        $acs = $this->access->accessPage(Route::$controller, Route::$routes[2]);
        if(!$acs){
            $this->errorPage('Доступ к данной странице ограничен правами пользователя.', 'Ограничение доступа');
            $this->action();
            exit();
        }
        // добавить свойство юзеру, которое разрешает просмотр
    }
    
    
    /**
     * @param string $url Перенаправляет на указанный адрес
     */
    public function redirect($url) {
        if(App::param('content_type') != 'json'){
            header('Location: '.$url);
            exit();
        }
        else{
            $this->action();
        }
    }
    
    
    /**
     * Перенаправление на главную страницу
     */
    public function goHome() {
        $this->redirect('/');
    }
    
    
    /**
     * Перенаправление на предыдущую страницу
     */
    public function goBack() {
        // сделать проверку на отсутствие левого домена
        $this->redirect(App::param('referer'));
    }
    
    
    /**
     * Перезагружает текущую страницу
     */
    public function reload() {
        // сделать проверку на отсутствие левого домена
        $this->redirect(Route::$uri);
    }


    /**
     * Постоянное перенаправление 301 редирект
     * @param string $url 
     * @return void
     */
    static function redirect301($url) {
        header($_SERVER['SERVER_PROTOCOL'] . " 301 Moved Permanently");
        header("Location: /" . $url);
        exit();
    }

    
    /**
     * Разбирает и дезинфицирует GET-данные
     * 
     * @param array $get Массив данных key-value
     * @return array
     */
    public function setGet($get) {
        if(is_array($get)){
            foreach($get as $key => $val){
                $this->get[$key] = $this->setGet($val);
            }
        }else{
            return $this->sanitization($get);
        }
    }


    /**
     * Возвращает все занчения или значение по ключу
     * @param string $key (не обязательно) Ключ к значению
     * @return array|mixed
     */
    public function getGet($key=false) {
        if($key){
            return $this->get[$key];
        }
        return $this->get;
    }

    
    /**
     * Разбирает и дезинфицирует POST-данные
     * 
     * Если передана правильная капча, то $this->data['captcha'] = TRUE;
     * @param array $post Массив данных key-value
     * @return array
     */
    public function setPost($post) {
        if(is_array($post)){
            foreach($post as $key => $val){
                $this->post[$key] = $this->setPost($val);
                if($key == 'captcha'){ $this->captcha($this->post[$key]);}
            }
        }else{
            return $this->sanitization($post);
        }
    }


    /**
     * Возвращает значение по ключу
     * @param string $key Ключ к значению
     * @return mixed
     * @deprecated
     */
    public function get_def($key) {
        return $this->post[$key];
    }


    /**
     * Возвращает все занчения или значение по ключу
     * @param string $key (не обязательно) Ключ к значению
     * @return array|mixed
     */
    public function getPost($key=false) {
        if($key){
            return $this->post[$key];
        }
        return $this->post;
    }


    /**
     * Удаляет все занчения или значение по ключу
     * @param string $key (не обязательно) Ключ к значению
     * @return void
     */
    public function unsetPost($key=false) {
        if($key){
            unset($this->post[$key]);
            return;
        }
        unset($this->post);
    }


    /**
     * Удаляет теги, крайние пробелы и экранирует кавычки
     * @param string $str 
     * @return string
     */
    public function sanitization($str) {
        // удаление хтмл и пхп тегов
        $str = htmlspecialchars( strip_tags( trim( $str ) ) ); 
        // удаляем экранирование, если включены "Волшебные кавычки"
        if( get_magic_quotes_gpc () ){
            $str = stripslashes ($str);
        }
        return addslashes($str); // экранируем кавычку
    }


    public function captcha($param){
        $this->data['use_captcha'] = TRUE;
        if($param && $param == $_SESSION['captcha']){
            $this->data['captcha'] = TRUE;
        }
    }

}