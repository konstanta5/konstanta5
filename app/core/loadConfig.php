<?php


/**
 * @autor: fedornabilkin icq: 445537491
 */
class loadConfig {
    
    private $file;
    
    public function __construct($name) {
        $this->file = APP_DIR . '/config/' .$name. '.php';
    }
    
    /**
     * @return array|false
     */
    public function load() {
        if(is_file($this->file)){
            return require $this->file;
        }
        return false;
    }
}
