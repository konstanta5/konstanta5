<?php

/* 
 * @autor: fedornabilkin icq: 445537491
 */

class Controller_Uploads extends Controller {
    
    /**
     * Для работы с классом Upload
     */
    public $upload;
    /**
     * Название сущности
     */
    private $entity;
    
    
    function __construct() {
        $this->upload = new Upload();
        parent::__construct();
    }
    
    
    function action_index() {
        $this->errorPage();
        return $this;
    }
    
    
    /**
     * Загружает файл картинки и изменяет адрес в БД
     */
    function action_image() {
        $this->upload->file = 'img'; // name input
        $this->entity = ($this->getPost('entity')) ? $this->getPost('entity'): 'news';
        // если загружен
        if (is_uploaded_file($_FILES[$this->upload->file]['tmp_name'])) {
            // проверка на ошибки
            if(!$this->upload->uploadValidate()){
                $this->session->addFlash(array('error'=>true, 'alert' => implode(', ', $this->upload->error)));
                return $this;
            }
            
            // имя и путь
            $this->content['image_link'] = '/img/entity/' .$this->entity. '_' .time(). '.' .$this->upload->file_type;
            // если перенесен в дирректорию
            if(!$this->upload->uploadFile($_FILES[$this->upload->file]['tmp_name'], ROOT_DIR . $this->content['image_link'])){
                $this->session->addFlash(array('error'=>true, 'alert' => implode(', ', $this->upload->error) ));
                unset($this->content['image_link']);
            }else{
                $this->content['status'] = 1;
            }
        }else{
            
            $this->content['files_array'] = $_FILES;
            $this->session->addFlash(array('error'=>true, 'alert' => 'Файл не загружен'));
        }
        
        if($this->getPost('iframe') && $this->content['status'] == 1){
            $idarea = $this->getPost("idarea");
            //$js = '<script>window.parent.$("#'.$idarea.'").insertImage("'.$xml->links->image_link.'","'.$xml->links->thumb_link.'").closeModal().updateUI();</script>';
            $js = '<script>window.parent.$("#'.$idarea.'").insertImage("'.$this->content['image_link'].'").closeModal().updateUI();</script>';
            $html = '<html><body>OK'.$js.'</body></html>';
            exit($html);
        }
        
        return $this;
    }
    
    
    /**
     * Загружает файл картинки и изменяет адрес в БД
     */
    function action_logo() {
        $this->entity_id = $this->getPost('eid');
        $this->entity = $this->getPost('entity');
        $img_type = ($this->getPost('type')) ? $this->getPost('type'): 'logo'; // table field name
        if($this->entity_id < 1){
            $this->session->addFlash(array('error'=>true, 'alert' => 'Не найден id'));
            return $this;
        }
        // если загружен
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
//            $this->file_type = $this->upload->getFileType($_FILES['file']['name']);
            // проверка на ошибки
            if(!$this->upload->uploadValidate()){
                $this->session->addFlash(array('error'=>true, 'alert' => implode(', ', $this->upload->error)));
                return $this;
            }
            
            // имя и путь
            $this->content[$img_type] = $this->entity. '_' .$img_type. '_' .$this->entity_id. '.' .$this->upload->file_type;
            // если перенесен в дирректорию
            if($this->upload->uploadFile($_FILES['file']['tmp_name'], ROOT_DIR . '/img/entity/' .$this->content[$img_type])){
                // в БД
                $model = 'Model_'.$this->entity;
                $this->setPost(array($img_type=>$this->content[$img_type]));
                $this->unsetPost('url');
                
                $this->entity_model = new $model($this->entity);
                $res = $this->entity_model->exceptFields($this->getPost())->save($this->entity_id);
                
            }
            else{
                $this->session->addFlash(array('error'=>true, 'alert' => 'Ошибка загрузки'));
            }
        }
        return $this;
    }
    
    
    /**
     * Проверка на ошибки при загрузке
     * @return boolean
     */
//    public function uploadValidate() {
//        // проверка ошибок загрузки
//        if($_FILES['file']['error'] > 0){
//            $this->session->addFlash(['alert' => 'Файл загружен с ошибкой']);
//            return false;
//        }
//        
//        // проверка на тип файла
//        if(!in_array($this->file_type, $this->access_type)){
//            $this->session->addFlash(['alert' => 'Недопустимый формат файла']);
//            return false;
//        }
//        
//        // проверка на размер
//        
//        return true;
//    }
    
    
    /**
     * Сохраняет файл по указанному пути
     * @param string $tmp_file Путь к временному файлу
     * @param string $dest_file Путь к будущему файлу
     * @return boolean
     */
//    public function uploadFile($tmp_file, $dest_file) {
//        if(move_uploaded_file($tmp_file, $dest_file)){
//            return true;
//        }
//        return false;
//    }
    
    
    /**
     * @param string $file Имя файла для определения формата
     * @return string
     */
//    public function getFileType($file) {
//        $si = new SimpleImage;
//        return $si->getType($file);
//    }
    
}