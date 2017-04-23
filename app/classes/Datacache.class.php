<?php

/**
 * Сериализует массив и сохраняет в файл
 * Извлекает из файла и десериализует массив
 *
 * @author fedornabilkin icq: 445537491
 */
class Datacache {
    

    /**
     * Основная директория, в которой будут хранится файлы кеша
     */
    public $cach_dir = 'temp';
    
    /**
     * Поддиректория для группировки
     */
    public $system_dir = 'system';
    
    /**
     * Хранит название файла
     */
    private $_file;
    /**
     * Время жизни файлов в кеше в секундах
     */
    private $timelive = 600;

    /**
     * Устанавливает время жизни кэша
     * @return object $this
     * @param integer $time Время в секундах
     */
    public function setTimelive($time) {
        $this->timelive = intval($time);
        return $this;
    }

    /**
     * @param $file Имя файла без расширения
     * @example 'top_user_today' Название файла, в котором будут хранится данные
     * @return array Десериализует данные из файла
     */
    function getArray($file) {
        $this->_file = $file;
        
        if(!file_exists($this->getPath())){
            return false;
        }elseif($this->getLastModifiedFile() < (time() - $this->timelive)){
            return false;
        }
        
        $str = file_get_contents($this->getPath());
        return unserialize($str);        
    }
    
    /**
     * @param $file Имя файла без расширения
     * @param $arr массив с данными, которые необходимо записать в файл
     * @example 'top_user_today', array(0,1,2)  название файла и массив
     * @return bolean Сохраняет сериализованные данные в файл
     */
    public function setArray($file, $arr) {
        $this->_file = $file;
        
        $fp=fopen($this->getPath(),'w');
        fclose($fp);
        chmod($this->getPath(), 0666);
        
        if(!file_exists($this->getPath())){
            return false;
        }
        $str = serialize($arr);
        
        $fp=fopen($this->getPath(),'w'); 
          flock($fp, LOCK_EX); 
            fwrite($fp, $str); 
          flock($fp, LOCK_UN); 
        fclose($fp);
    }
    
    /**
     * @return string Формирует путь к файлу для чтения или записи
     */
    public function getPath(){
        return $this->cach_dir .'/'. $this->system_dir .'/'. $this->_file .'.php';
    }
    
    /**
     * @return ineger Время последнего изменения файла в секундах
     */
    private function getLastModifiedFile() {
        return filemtime($this->getPath());
    }
    
}
