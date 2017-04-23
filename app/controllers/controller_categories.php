<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */

class Controller_Categories extends Controller {
    
    /**
     * Содержит объект для работы с таблицей currency
     */
    public $model;
    /**
     * Свойства для смены ранжирования валют
     */
    private $rank;
    private $row;
    private $id;
    
    
    function __construct() {
        $this->id = strtolower(Route::$routes[3]);
        $this->model = new Model_Categories();
        parent::__construct();
    }
    
    
    public function action_index() {
        $this->content['breadcrumbs'][0] = array();
        $fields = array('id','name');
        $this->content['rows'] = $this->model->getRows('', $fields, 'ORDER BY rank ASC');
        
        $this->content['title'] = 'Категории';
        return $this;
    }
    
    
    public function action_add() {
        if(!in_array(App::$user->status, App::param('admin'))){
            $this->session->addFlash(array('error'=>true, 'alert' => 'Доступ ограничен.'));
            return $this;
        }
        if($this->getPost()){
            $this->setPost(array('rank' => $this->model->getMaxRank() + 1));
            if($this->getPost('name')){
                $add = $this->model->exceptFields($this->getPost())->save();
                if($add){
                    $this->session->addFlash(array('alert' => 'Запись успешно добавлена.'));
                    // redirect
                    $this->redirect('/' . Route::$controller);
                }else{
                    $this->session->addFlash(array('error'=>true, 'alert' => 'Ошибка добавления!'));
                }
            }else{
                $this->session->addFlash(array('error'=>true, 'alert' => 'Необходимо добавить название.'));
            }
            
        }
        
        $this->content['row'] = $this->getPost();
        
        $this->content['title'] = 'Добавить категорию';
        return $this;
    }
    
    
    public function action_edit() {
        if($this->getPost() && in_array(App::$user->status, App::param('admin'))){
            
            $update = $this->model->exceptFields($this->getPost())->save($this->id);
            if($update){
                // аписать в сессию на один раз
                $this->session->addFlash(array('alert' => 'Запись успешно изменена.'));
                // redirect
                $this->redirect('/' . Route::$controller);
            }
            else{
                $this->session->addFlash(array('error'=>true, 'alert' => 'Ошибка редактирования!'));
            }
        }
        
        $fields = array('id', 'name');
        $this->content['row'] = $this->model->getData($this->id, $fields);
        $this->content['title'] = 'Редактировать категорию <b>' . $this->content['row']['name'] . '</b>';
        
        if(!$this->content['row']){
            $this->errorPage();
        }
        
        return $this;
    }
    
    
    public function action_rankup() {
        $row = $this->getCategoryRow();
        $next = $this->model->nextCategory($row['rank']);
        
        if($next['rank'] > 0){
            $up = $this->model->exceptFields(array('rank'=>$next['rank']))->save($row['id']);
            $up_next = $this->model->exceptFields(array('rank'=>$row['rank']))->save($next['id']);
        }
        elseif($row['rank'] > 0){
           $up = $this->model->exceptFields(array('rank'=>$row['rank']))->save($row['id']); 
        }
        $this->session->addFlash(array('alert' => 'Перемещена'));
        return $this;
    }
    
    
    public function action_rankdown() {
        $row = $this->getCategoryRow();
        $prev = $this->model->prevCategory($row['rank']);
        
        if($prev['rank'] > 0){
            $up = $this->model->exceptFields(array('rank'=>$prev['rank']))->save($row['id']);
            $up_prev = $this->model->exceptFields(array('rank'=>$row['rank']))->save($prev['id']);
        }
        elseif($row['rank'] > 0){
           $up = $this->model->exceptFields(array('rank'=>$row['rank']))->save($row['id']); 
        }
        $this->session->addFlash(array('alert' => 'Перемещена'));
        return $this;
    }
    
    
    public function action_remove() {
        if(in_array(App::$user->status, App::param('admin'))){
            if($this->model->delete($this->id)){
                $this->session->addFlash(array('alert' => 'Категория удалена'));
            }else{
                $this->session->addFlash(array('error'=>true, 'alert' => 'Ошибка удаления!'));
            }
        }else{
            $this->session->addFlash(array('error'=>true, 'alert' => 'Недостаточно прав!'));
        }
        
        return $this;
    }
    
    
    /**
     * Получает данные валюты
     * @return array()
     */
    public function getCategoryRow() {
        $row = $this->model->getData($this->id, array('id', 'rank'));
        $row['rank'] = ($row['rank'] < 1) ? ($this->model->getMaxRank() + 1) : $row['rank'];
        return $row;
    }
}