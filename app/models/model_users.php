<?php

/**
 * @autor fedornabilkin icq: 445537491
 */
class Model_Users extends Model {
    
    /**
     * fields
     * @see setParams()
     */
    public $id;
    public $login;
    public $balance;
    public $credit;
    public $rating;
    public $avatar;
    public $access = 'user';
    
    
    
    public function __construct($table='users') {
        parent::__construct($table);
    }
    
    
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
    
    
    /**
     * @param string $login
     * @param array $fields массив полей, которые необходимо вернуть из БД
     * @return array|false
     */
    public function findByLogin($login, $fields = array('id')) {
        return $this->getData(array('login'=>$login), $fields);
    }
    
    
    /**
     * @param string $params
     * @param array $fields массив полей, которые необходимо вернуть из БД
     * @return array|false
     */
    public function findByLoginMail($params, $fields = array('id')) {
        $select = implode(", ", $fields);
        $sql = "SELECT $select FROM users WHERE login = '".$params['login']."' OR mail = '".$params['mail']."'";
        return $this->super_query($sql);
    }
    
    
    /**
     * @param integer $id
     * @param array $fields массив полей, которые необходимо вернуть из БД
     * @return array|false
     */
    public function findById($id, $fields = array('login')) {
        return $this->getData($id, $fields);
    }
    
    
    /**
     * @param integer $key
     * @param array $fields массив полей, которые необходимо вернуть из БД
     * @return array|false
     */
    public function findBySecretKey($key, $fields = array('id')) {
        return $this->getData(array('secret_key'=>$key), $fields);
    }
    
    
    /**
     * @param $password оригинальный пароль, который вводит пользователь или формируется при регистрации
     * @param $salt соль для степени защиты (у одинаковых паролей будут разные хэши)
     * @return string Кодирует пароль с солью.
     */
    public function encodePassword($password, $salt) {
        return md5($password . md5($salt));
    }
    
    
    /**
     * @param string $password оригинальный пароль, который вводит пользователь
     * @param string $salt соль из БД
     * @param string $hash password hash из БД
     * @return boolean
     */
    public function validatePassword($password, $salt, $hash) {
        return $this->encodePassword($password, $salt) == $hash;
    }
    
    
    /**
     * Создает секретный ключ с меткой времени через _
     * @return string
     */
    public function generateSecretKey() {
        $time = time();
        return md5($time) . '_' . $time;
    }
    
    
    /**
     * Проверяет время жизник ключа
     * @param string $key
     * @return boolean
     */
    public function validateExpireSecretKey($key) {
        $parts = explode('_', $key);
        return (time() - end($parts)) < App::param('secretKeyExpire');
    }
    
}
