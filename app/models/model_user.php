<?php

/**
 * @autor fedornabilkin icq: 445537491
 */
class Model_User {
    
    /**
     * integer id
     */
    public $id;
    
    /**
     * string status
     */
    public $status = 'user';
    
    /**
     * boolean
     */
    public $thisPage = false;
    
    
    /**
     * @param array $params Массив значений array('id'=>124, 'status'=>'moderator')
     * @return object|false
     */
    public function setParams($params) {
        if(!is_array($params)){
            return false;
        }
        foreach ($params as $key => $value) {
            $this->$key = $value;
        }
    }
    
}
