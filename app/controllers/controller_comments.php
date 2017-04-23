<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */

class Controller_Comments extends Controller {
    
    /**
     * Содержит объект для работы с таблицей comments
     */
    public $model;
    
    /**
     * id комментария
     */
    private $cid;
    
    
    function __construct() {
        $this->cid = Route::$routes[3];
        $this->model = new Model_Comments('comments');
        parent::__construct();
    }
    
    
    public function action_index() {
        $this->content['rows'] = $this->model->getComments('', array('id', 'comment', 'author', 'author_ip', 'type', 'entity', 'entity_id', 'moderator', 'time'), 'ORDER BY time DESC');
        $this->content['title'] = 'Список отзывов';
        return $this;
    }
    
    
    public function action_add() {
        if($this->getPost()){            
            $this->setPost(array('time'=>time(), 'author_ip'=>App::param('ip')));
            if(!$this->getPost('author')){
                $this->setPost(array('author'=>'Гость'));
            }
            $add = $this->model->exceptFields($this->getPost())->save();
            if(!$add){
                $this->session->addFlash(array('alert' => 'Ошибка Добавления!'));
                $this->content['error'] = true;
            }
            
            $this->session->addFlash(array('alert' => 'Отзыв успешно добавлен.'));
//            $this->content['row'] = $this->getPost();
        }
        else{
            $this->session->addFlash(array('alert' => 'Ошибка! Не получены данные отзыва.'));
        }
        
        $this->goBack();
    }
    
    
    public function action_edit() {
        if($this->getPost()){
            $update = $this->model->exceptFields($this->getPost())->save($this->cid);
            if(!$update){
                $this->session->addFlash(array('alert' => 'Ошибка редактирования!'));
                $this->content['error'] = true;
            }
            $this->content['row'] = $this->getPost();
        }
        else{
            $this->errorPage('Не получены данные для редактирования.', 'Ошибка данных');
        }
        
        return $this;
    }
    
    
    public function action_remove() {
        // получим данные комментария
        $row = $this->model->getCommentById($this->cid, array('entity', 'entity_id', 'moderator'));
        // пересчет комментариев в entity
        if($row['entity_id'] > 0){
            $set = ($row['moderator'] > 0) ? '`comments` - 1': '`comments`';
            $sql = "UPDATE ".$row['entity']." SET `comments` = $set WHERE `id` = ".$row['entity_id']." ";
            $this->model->query($sql);
            
            // удалим коммент
            $remove = $this->model->delete($this->cid);
        }
        
        if(!$remove){
            $this->session->addFlash(array('alert' => 'Ошибка удаления!'));
            $this->content['error'] = true;
        }
        else{
            $this->session->addFlash(array('alert' => 'Отзыв удален'));
        }
        $this->goBack();
    }
    
    
    public function action_approve() {
        // approve comment
        $row = $this->model->getCommentById($this->cid, array('entity', 'entity_id', 'moderator'));
        if($row){
            $this->setPost(array('moderator'=>  App::$user->id));
            $this->unsetPost('type');
            $update = $this->model->exceptFields($this->getPost())->save($this->cid);
            if($update){
                // add count comments
                $sql = "UPDATE ".$row['entity']." SET `comments` = `comments` + 1 WHERE `id` = ".$row['entity_id']." ";
                $this->model->query($sql);
                $this->session->addFlash(array('alert' => 'Отзыв одобрен.'));
            }
            elseif(!$update){
                $this->session->addFlash(array('alert' => 'Ошибка!'));
                $this->content['error'] = true;
            }
        }
        else{
            $this->content['error'] = true;
            $this->session->addFlash(array('alert' => 'Недостаточно прав'));
        }

        $this->goBack();
    }
    
    /**
     * Получает количество новых комментариев
     */
    public function action_new() {
        $this->content['rows'] = $this->model->getNewComments();
        $this->content['count'] = count($this->content['rows']);
        
        return $this;
    }
}