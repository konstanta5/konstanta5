<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */
class Controller_News extends Controller {

    /**
     * Содержит объект для работы с таблицей
     */
    public $model;

    /**
     * id страницы
     */
    private $id;
    private $fields = array('id','anons','tizer','title','content','author','hide','description','keywords','time');

    function __construct() {
        $this->id = strtolower(Route::$routes[3]);
        $this->model = new Model_News();
        parent::__construct();
    }

    public function action_index() {
        $this->content['rows'] = $this->model->getNewsRows('', $this->fields, 'ORDER BY id DESC');
        $this->content['count']['stat']['all'] = count($this->content['rows']);
        $this->content['title'] = 'Все новости';
        return $this;
    }

    public function action_my() {
        $this->content['rows'] = $this->model->getNewsRows(array('author'=>  App::$user->id), $this->fields, 'ORDER BY id DESC');
        $this->content['count']['stat']['all'] = count($this->content['rows']);
        $this->content['title'] = 'Мои новости';
        $this->view_file = 'index';
        return $this;
    }

    public function action_read() {
        $this->content['row'] = $this->model->getNewsRow($this->id, $this->fields);
        if($this->content['row']['hide'] == 1 && !in_array(App::$user->status, App::param('moderator'))){
            $this->errorPage();
            return $this;
        }
        
        // если есть страница
        if ($this->content['row']['id'] > 0 ) {
            $this->content['title'] = $this->content['row']['title'];
            $this->content['row']['author'] = App::$user->getData($this->content['row']['author'], array('login'))['login'];
            
            $bb = new BBCode($this->content['row']['content']);
            $this->content['row']['content'] = $bb->getText();
            
            $this->content['ogp']['og:title'] = $this->content['row']['anons'];
            $this->content['ogp']['og:url'] = App::param('http_home_url') . Route::$uri;
            $this->content['ogp']['og:type'] = 'article';
            $this->content['ogp']['article:published_time'] = date('Y-m-dTH:i:s', $this->content['row']['time']);
            $this->content['ogp']['article:author'] = $this->content['row']['author'];
            $this->content['ogp']['og:image'] = App::param('http_home_url'). '/img/news/' .$this->content['row']['tizer'];
            $this->content['ogp']['og:description'] = $this->content['row']['description'];
            $this->content['ogp']['og:site_name'] = App::param('site_name');
        }
        
        // другие новости
        $fields = array('id','anons','tizer');
        $this->content['othernews'] = $this->model->setLimit(array(0,2))
                ->getRows(array('anons !'=>'', 'hide !'=>1, 'id <'=>$this->content['row']['id']-1), $fields, 'ORDER BY time DESC');

        return $this;
    }

    /**
     * Добавление
     */
    public function action_add() {
        if(!in_array(App::$user->status, App::param('content'))){
            $this->errorPage('Недостаточно прав', 'Ограничение доступа');
            return $this;
        }
        if ($this->getPost()) {
            if(!$this->getPost('anons') or !$this->getPost('title')){
                $this->session->addFlash(array('error'=>true, 'alert' => 'Не указан анонс или заголовок!'));
                return $this;
            }
            $this->setPost(array('author'=>App::$user->id, 'edit_time' => time(), 'time' => time()));
            $add = $this->model->exceptFields($this->getPost())->save();
            if ($add) {
                $this->id = $add;
                $this->uploadTizer();
                $this->session->addFlash(array('alert' => 'Новость успешно добавлена.'));
                // redirect
                $this->redirect('/' .Route::$routes[1]. '/read/' .$add);
            } else {
                $this->session->addFlash(array('error'=>true, 'alert' => 'Ошибка добавления!'));
            }
        }
        
        $this->content['categories'] = $this->getCategories();
        $this->content['row'] = $this->getPost();
        $this->content['title'] = 'Добавить новость';
        return $this;
    }

    /**
     * Редактирование
     */
    public function action_edit() {
        if(!$this->checkMyNew() && !in_array(App::$user->status, App::param('moderator'))){
            $this->errorPage('Недостаточно прав', 'Ограничение доступа');
            return $this;
        }
        if ($this->getPost()) {            
            $this->setPost(array('edit_time' => time()));
            $update = $this->model->exceptFields($this->getPost())->save($this->id);
            if ($update) {
                // redirect
                $this->session->addFlash(array('alert' => 'Данные успешно сохранены.'));
                $this->redirect('/' .Route::$routes[1]. '/read/' .$this->id);
            } else {
                $this->session->addFlash(array('error'=>true, 'alert' => 'Ошибка редактирования!'));
            }
        }

        $this->content['categories'] = $this->getCategories();
        $this->content['row'] = $this->model->getData($this->id);

        $this->content['title'] = 'Редактировать новость <strong>' . $this->content['row']['title'] . '</strong>';
        return $this;
    }

    
    /**
     * Скрыть/отобразить
     * @return object $this
     */
    public function action_hide() {
        if ($this->getPost()&& in_array(App::$user->status, App::param('moderator'))) {
            $old = $this->getPost('hide');
            $main = ($this->getPost('hide') == 1) ? 0 : 1;
            $this->setPost(array('hide' => $main));

            $res = $this->model->exceptFields($this->getPost())->save($this->id);
            if (!$res) {
                $this->content['error'] = true;
                $this->setPost(array('hide' => $old));
            }
            $this->content['hide'] = $this->getPost('hide');
        } else {
            $this->errorPage('Данные не получены', 'Ошибка данных');
        }

        return $this;
    }

    
    /**
     * Удаление
     * @return object $this
     */
    public function action_uptizer() {
        $this->uploadTizer();
        return $this;
    }

    
    /**
     * Удаление
     * @return object $this
     */
    public function action_remove() {
        if (!in_array(App::$user->status, App::param('moderator'))) {
            $this->session->addFlash(array('error'=>true, 'alert' => 'Ошибка удаления.'));
            return $this;
        }
        $res = $this->model->delete($this->id);
        if($res){
            $this->session->addFlash(array('alert' => 'Новость удалена.'));
            $this->redirect('/' .Route::$routes[1]);
        } else{
            $this->session->addFlash(array('error'=>true, 'alert' => 'Ошибка удаления.'));
        }
        return $this;
    }
    
    
    /**
     * Проверка новости на принадлежность автору
     * @return boolean
     */
    public function checkMyNew() {
        return App::$user->id == $this->model->getData($this->id, array('author'))['author'];
    }
    
    
    /**
     * Загружает файл картинки и изменяет адрес в БД
     */
    function uploadTizer() {
        $upload = new Upload();
        $upload->access_size = 2097152; // 2mb
        
        // если загружен
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            // проверка на ошибки
            if(!$upload->uploadValidate()){
                $this->session->addFlash(array('error'=>true, 'alert' => implode(', ', $upload->error)));
                $this->content['error'] = true;
                return;
            }
            
            // имя и путь
            $this->content['tizer'] = 'tizer_' .$this->id. '.' .$upload->file_type;
            // если перенесен в дирректорию
            if($upload->uploadFile($_FILES['file']['tmp_name'], ROOT_DIR . '/img/news/' .$this->content['tizer'])){
                // в БД
                $res = $this->model->exceptFields(array('tizer'=>$this->content['tizer']))->save($this->id);
                chmod(ROOT_DIR . '/img/news/' .$this->content['tizer'], 0666);
            }
            else{
                $this->session->addFlash(array('error'=>true, 'alert' => 'Ошибка загрузки'));
            }
        }
        else{
            $this->session->addFlash(array('error'=>true, 'alert' => 'Файл не передан на сервер'));
        }
    }
    
    
    /**
     * Возвращает категории
     * @return array
     */
    public function getCategories() {
        $model = new Model_Categories();
        return $model->getRows('', array('id','name'), 'ORDER BY rank ASC');
    }

}
