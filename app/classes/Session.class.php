<?php

/**
 * @author fedornabilkin
 */
class Session {
    
    private static $data = array();
//    private static $flash = array();
    private $flashPrefix = '_flash';
    
    
    function __construct() {
        session_start();
        $this->session_get();
    }
    
    
//    public static function instance($param = FALSE) {
//        
//    }
    
    /**
     * Устанвливает значение
     * @param string $key Ключ значения
     * @param mixed $val значение
     */
    private function set($key, $val) {
        self::$data[$key] = $val;
        $_SESSION[$key] = $val;
    }
    
    
    /**
     * Возвращает значение по ключу
     * @param string $key Ключ к значению
     * @return mixed
     */
    public function get($key, $default = null) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
//        return self::$data[$key];
    }
    
    
    /**
     * @param array $params Масив значений для записи в сессию
     * @return object $this
     */
    function session_set($params){
        foreach($params as $key=>$val){
            $_SESSION[$key] = $val;
            $this->set($key, $val);
        }
        return $this;
    }
    
    
    function session_get(){
        foreach($_SESSION as $key=>$val){
            $this->set($key, $val);
        }
    }
    
    
    /**
     * Удаляет значение по ключу
     * @param string $key Ключ к значению
     * @return void
     */
    public function delete($key) {
        unset($_SESSION[$key], self::$data[$key]);
    }
    
    
    /**
     * Удаляет сессию и все данные
     * @return void
     */
    function destroy(){
        session_destroy();
        self::$data = array();
    }
    
    /**
     * Одноразовые сессии для вывода пользователю сообщений, которые тут же удаляются из сессии
     * Например при добавлении новой записи, после редиректа выводится сообщение об успешном добавлении.
     * При перезагружке страницы сообщение выводится не будет.
     */
    
    /**
     * Добавляет одноразовые сессии
     * @param array $params $key => $value
     * @return void
     */
    public function addFlash($params) {
        foreach ($params as $key => $value) {
            $this->set($key . $this->flashPrefix, $value);
        }
    }
    
    
    /**
     * Возвращает значение одноразовой сессии по ключу
     * @param string $key Ключ к значению
     * @return mixed
     */
    public function getFlash($key, $default = null) {
        $flash_key = $key . $this->flashPrefix;
        $val = isset($_SESSION[$flash_key]) ? $_SESSION[$flash_key] : $default;
        $this->removeFlash($key);
        return $val;
    }
    
    
    /**
     * Удаляет значение одноразовой сессии по ключу
     * @param string $key Ключ к значению
     * @return void
     */
    public function removeFlash($key) {
        $flash_key = $key . $this->flashPrefix;
        $this->delete($flash_key);
    }
    
}