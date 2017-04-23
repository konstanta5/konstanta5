<?php

/**
 * @author fedornabilkin icq: 445537491
 */
class Upload {
    
    /**
     * Название файла в массиве $_FILES['file']['name'];
     */
    public $file = 'file';
    /**
     * Загруженные файлы
     */
    public $files = array();
    /**
     * Тип файла
     */
    public $file_type;
    /**
     * Размер файла
     */
    public $file_size;
    /**
     * Допустимые форматы файлов
     */
    public $access_type = array('png', 'gif', 'jpg', 'jpeg');
    /**
     * Допустимый размер размер 2mb - 2097152, 1mb - 1048576
     */
    public $access_size = 2097152;
    /**
     * errors
     */
    public $error = array();
    
    
    function __construct(){
//        if($_FILES){
//            foreach ($_FILES as $key => $value) {
//                if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
//                    $this->files[] = $_FILES[$key];
//                }
//            }
//            
//        }
    }
    
    
    /**
     * Проверка на ошибки при загрузке
     * @return boolean
     */
    public function uploadValidate() {
        // проверка ошибок загрузки
        if($_FILES[$this->file]['error'] > 0){
            $this->error[] = 'Файл загружен с ошибкой';
            return false;
        }
        
        // проверка на размер
        if($this->access_size < $_FILES[$this->file]['size']){
            //$this->error[] = 'Файл не должен быть больше ' .$this->getSizeFormat($_FILES[$this->file]['size']);
            $this->error[] = 'Файл превышает допустимый размер';
            return false;
        }
        
        // проверка на тип файла
        $this->file_type = $this->getFileType($_FILES[$this->file]['name']);
        if(!in_array($this->file_type, $this->access_type)){
            $this->error[] = 'Недопустимый формат файла';
            return false;
        }
        
        return true;
    }
    
    
    /**
     * Сохраняет файл по указанному пути
     * @param string $tmp_file Путь к временному файлу
     * @param string $dest_file Путь к будущему файлу
     * @return boolean
     */
    public function uploadFile($tmp_file, $dest_file) {
        if(move_uploaded_file($tmp_file, $dest_file)){
            return true;
        }
        $this->error[] = 'Файл не скопирован';
        return false;
    }
    
    
    /**
     * @param string $file Имя файла для определения формата
     * @return string
     */
    public function getFileType($file) {
        $si = new SimpleImage;
        return $si->getType($file);
    }
    
    
    /**
     * Возвращает преобразованный размер файла
     * @param integer $size Размер файла в байтах
     * @return string
     */
    public function getSizeFormat($size) {
        $text = 'Байт';
        switch(true){
            case ($size > 1024):
                $size = $size / 1024;
                $text = 'Кб';
            case ($size > 1024):
                $size = $size / 1024;
                $text = 'МБ';
            case ($size > 1024):
                $size = $size / 1024;
                $text = 'ГБ';
            case ($size > 1024):
                $size = $size / 1024;
                $text = 'ТБ';
        }
        
        return round($size,2). ' ' .$text;
    }
    
}
