<?php

/**
 * @param array $access Массив страниц с id пользователей
 * @param integer $uid id пользователя
 * @autor fedornabilkin icq: 445537491
 */
class Access {
    
    /**
     * Содержит массив страниц с id пользователей, которым разрешен просмотр
     */
    private static $access;
    
    /**
     * Содержит id пользователя
     */
    private static $uid;
    
    
    function __construct($access, $uid = 0) {
        self::$access = $access;
        self::$uid = $uid;
    }
    
    
    /**
     * @param string $ctrl имя контроллера
     * @param string $action имя действия
     * @return boolean
     */
    public function accessPage($ctrl, $action) {
        $page = self::$access[$ctrl][$action];
        if(!is_array($page)){
            return true;
        }
        if(in_array(self::$uid, $page)){
            return true;
        }
        return false;
    }
    
}
