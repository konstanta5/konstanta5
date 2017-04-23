<?php

/**
 * @autor fedornabilkin icq: 445537491
 */

class App
{
    
    /**
     * object request (post, get)
     */
    public static $request;
    
    /**
     * object user
     */
    public static $user;
    
    /**
     * Содержит массив с конфигурацией
     */
    private static $cfg;
    
    
    /**
     * Добавляет массив значений
     * @param array $param Массив ключ-значение
     */
    static function setParam($param) {
        foreach($param as $key => $val){
            self::$cfg[$key] = $val;
        }
    }
    
    
    /**
     * Устанавливает массив значений
     * @param array $params Массив ключ-значение
     */
    static function setParams($params) {
        self::$cfg = $params;
    }
    
    
    /**
     * Возвращает значение по ключу
     * @param string $name ключ для получения значения
     * @return mixed
     */
    static function param($name) {
        return self::$cfg[$name];
    }
    
    
    /**
     * Возвращает все параметры
     * @return array
     */
    static function getParams() {
        return self::$cfg;
    }
    
}