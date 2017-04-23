<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */
class Controller_Pages extends Controller {

    /**
     * Содержит объект для работы с таблицей pages
     */
    public $model;
    private $pid;

    function __construct() {
        $this->pid = strtolower(Route::$routes[3]);
        $this->model = new Model_Pages();
        parent::__construct();
    }

    public function action_index() {
        $fields = array('id','title', 'content', 'description', 'keywords','type');
        $this->content['rows'] = $this->model->getPagesRows('', $fields);
        $this->content['title'] = 'Список всех страниц';
        return $this;
    }

    public function action_read() {
        
        $fields = array('id','title', 'content','type', 'description', 'keywords','time');
        $this->content['row'] = $this->model->getPageById($this->pid, $fields);
        
        // если есть страница
        if ($this->content['row']['id'] > 0) {
            $this->content['title'] = $this->content['row']['name'] = $this->content['row']['title'];
            
            $bb = new BBCode($this->content['row']['content']);
            $this->content['row']['content'] = $bb->getText();
            $bb->setText($this->content['row']['content_after']);
            $this->content['row']['content_after'] = $bb->getText();
        }
        else{
            $this->errorPage();
        }

        return $this;
    }

    /**
     * Добавление
     */
    public function action_add() {
        if ($this->getPost()) {
            $this->setPost( array('time' => time()) );
            $add = $this->model->exceptFields($this->getPost())->save();
            if ($add) {
                // redirect
                $this->redirect('/pages/' . $add);
            } else {
                $this->session->addFlash(array('error' => true, 'alert' => 'Ошибка Добавления!'));
            }
        }

        $this->content['row'] = $this->getPost();

        $this->content['title'] = 'Добавить страницу';
        $this->view_file = 'edit';
        return $this;
    }

    /**
     * Редактирование
     */
    public function action_edit() {
        if ($this->getPost()) {
            $update = $this->model->exceptFields($this->getPost())->save($this->pid);
            if ($update) {
                // redirect
                $this->session->addFlash(array('alert' => 'Данные успешно сохранены.'));
                $this->redirect('/pages/' . $this->pid);
            } else {
                $this->session->addFlash(array('error' => true, 'alert' => 'Ошибка редактирования!'));
            }
        }

        $this->content['row'] = $this->model->getData($this->pid);
        if(!$this->content['row']['id']){
            $this->errorPage('Нет данных страницы');
        }

        $this->content['title'] = 'Редактировать страницу';
        return $this;
    }

    
    /**
     * Удаление
     * @return object $this
     */
    public function action_remove() {
        $res = $this->model->delete($this->pid);
        if($res){
            $this->session->addFlash(array('alert' => 'Страница удалена.'));
        } else{
            $this->session->addFlash(array('alert' => 'Ошибка.'));
            $this->content['error'] = true;
        }
        return $this;
    }

}