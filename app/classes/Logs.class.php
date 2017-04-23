<?php

/**
 * @author fedornabilkin icq: 445537491
 */
class Logs {
    
    /**
     * Основная директория, в которой будут хранится файлы логов
     */
    public $dir = 'logs';
    
    /**
     * Поддиректория для группировки
     */
    private $group_dir = false;
    
    /**
     * Хранит название файла
     */
    private $_file;
    
    /**
     * Режим открытия файла
     */
    private $_fileMode = 'a';
    
    /**
     * Устанавливает название поддиректории
     * @param string $dirname Название поддиректории
     * @return object $this
     */
    public function setGroupDir($dirname) {
        $this->group_dir = $dirname;
        return $this;
    }
    
    /**
     * Устанавливает название файла
     * @param string $file
     * @return object $this
     */
    public function setFileName($file) {
        $this->_file = $file;
        return $this;
    }
    
    /**
     * Устанавливает режим открытия файла
     * @param string $mode (w, a, a+ ...)
     * @return object $this
     */
    public function setFileMode($mode) {
        $this->_fileMode = $mode;
        return $this;
    }
    
    /**
     * @param string $str строка с данными, которые необходимо записать в файл
     * @return bolean Сохраняет данные в файл
     */
    public function write($str) {
        $time = 'd.m.y H:i:s';
        $this->_file = (!$this->_file) ? date('d-m-y'): $this->_file;
        
        if(!file_exists($this->getPath())){
            $fp=fopen($this->getPath(),'w');
            fclose($fp);
            chmod($this->getPath(), 0666);
        }
        
        $fp=fopen($this->getPath(), $this->_fileMode); 
          flock($fp, LOCK_EX); 
            fwrite($fp, date($time).' '.$str. "\n"); 
          flock($fp, LOCK_UN); 
        fclose($fp);
    }
    
    /**
     * @return string Формирует путь к файлу для чтения или записи
     */
    private function getPath(){
        $dir = $this->group_dir ? $this->group_dir .'/' : '';
        return $this->dir .'/'. $dir . $this->_file .'.txt';
    }
    
}
