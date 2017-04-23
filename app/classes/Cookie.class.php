<?php

/**
 * @author fedornabilkin
 */
class Cookie {
    
    private static $data = array();
    
    
    function __construct() {
        $this->getCookies();
    }
    
    
//    public static function instance($param = FALSE) {
//        
//    }
    
    
    /**
     * Устанавливает куку по ключу
     * @param string $name название куки
     * @param string $value значение куки
     * @param integer $days количество дней
     * @return object $this
     */
    public function set($name, $value, $days = 365) {
        if($days > 0){
            self::$data[$key] = $val;
        }
        setcookie($name, $value, time()+60*60*24*$days, '/');
        return $this;
    }
    
    
    /**
     * Получает куку по ключу
     * @param string $name
     * @return object $this
     */
    public function get($name) {
        return self::$data[$name];
    }
    
    
    /**
     * @param array $params Масив значений для записи в куки
     * @return object $this
     */
    function setCookies($params){
        foreach($params as $key=>$val){
            $this->set($key, $val);
        }
        return $this;
    }
    
    
    function getCookies(){
        foreach ($_COOKIE as $key => $value) {
            self::$data[$key] = $value;
        }
    }
    
    
    /**
     * Удаляет значение по ключу
     * @param string $key Ключ к значению
     * @return object $this
     */
    public function delete($key) {
        unset(self::$data[$key]);
        return $this->set($key, null, 0);
    }

    
}