<?php

/**
 * @author fedornabilkin icq: 445537491
 */
class Model extends Data {
    
    /**
     * Поля и значения, которые необходимо изменить
     */
    public $fields_edit = array();
    
    /**
     * Коллекция дочерних моделей, которые были вызваны
     */
    public static $child_models = array();
    
    /**
     * Названия полей, которые необходимо вернуть
     */
    protected $get_fields = array();
    /**
     * Все поля таблицы
     */
    private $table_fields = array();
    
    /**
     * Содержит объект класса Logs
     */
    private $cache = false;
    
    // конструктор грузится дочерними(наследуемыми) классами
    function __construct($table) {
        self::$child_models[] = get_class($this);
//        $this->set_table($table);
        parent::__construct($table);
    }

    
    /**
     * Инициализирует объект для работы с кэшем
     * @return object
     */
    public function initCache() {
        if(!$this->cache){
            $this->cache = new Datacache;
        }
        return $this->cache;
    }
    
    /**
     * Устанавливает названия полей, которые необходимо вернуть
     * @param array $fields массив с названиями полей
     * @return object $this
     */
    public function setFields($fields) {
        $this->get_fields = $fields;
        return $this;
    }
    
    
    /**
     * Исключает данные перед записью|обновлением, если в таблице нет указанного поля
     * @param array $params field=>value
     * @return object $this
     * @see save($wh)
     */
    public function exceptFields($params){
        $this->fields_edit = array(); // очищаем от возможных предыдущих данных
        $this->table_fields = $this->getFields();
        foreach ($params as $key => $value) {
            if(!in_array($key, $this->table_fields) or $value === null){
                // выводить сообщение о том, что некоторые поля не соответствуют
                // если автор админ
                continue;
            }
            $this->fields_edit[$key] = $value;
        }
        return $this;
    }
    
    
    /**
     * @param integer|array $wh (не обязательно)
     * @return boolean
     * @see exceptFields
     */
    public function save($wh=false) {
        if(!$this->fields_edit){
            return false;
        }
        if($wh){
            return $this->edit($wh, $this->fields_edit);
        }
        else{
            return $this->insert($this->fields_edit);
        }
    }
    
    
    /**
     * Возвращает SimpleXMLElement Object
     * @param string $xml данные в xml-формате
     * @return object
     */
    public function getSimpleXml($xml) {
        return simplexml_load_string($xml);
    }
    
    /**
     * @return string
     */
    public function rand_str($n) {
        $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $count = strlen($str)-1;
        for($i=0; $i<$n; $i++){
            $key .= $str{rand(0,$count)};
        }
        return $key;
    }
    
    
    /**
     * @param integer $cnt Задает длину строки
     * @return string Генерирует строку случайных символов для пароля, соли.
     */
    public function getRandomString($cnt = 3) {
        $letter = 'qwertyupasdfghkzxcvbnmQWERTYUPASDFGHKZXCVBNM';
        $numbers = '1234567890';
        $symbols = '!@#$%^&*';
        
        $salt = '';
        for($i=0; $i<$cnt; $i++){
            if($i%2){
                $salt .= $numbers[mt_rand(0, strlen($numbers))];
            }
            else{
                $salt .= $letter[mt_rand(0, strlen($letter))];
            }
            
        }
        
        return $salt;
    }
    
    
    /**
     * Преобразует возраст в дату рождения
     * @param string $age dd-mm-yy OR y-m
     * @return integer
     */
    public function normalizeBirthdate($age) {
        $parts = explode('-', $age);
        if(count($parts) != 2){
            return strtotime($age);
        }
        return mktime(0, 0, 0, date("m")-$parts[1],   date("d"),   date("Y")-$parts[0]);
        
    }
    
    
    /**
     * Преобразует возраст из даты рождения
     * @param integer $birthdate 
     * @return integer
     */
    public function normalizeAge($birthdate) {
        $str = 'год';
        $y = date('Y') - date('Y', $birthdate);
        $m = date('m') - date('m', $birthdate);
        switch(true){
            case ($y > 4 && $y < 21): {$str = 'лет'; break;}
            case ($y > 1 && $y < 5): {$str = 'года'; break;}
            case ($y === 1): {$str = 'год'; break;}
            case ($y === 0): {$str = ' Менее года'; $y=''; break;}
        }
        return $y. ' ' .$str;
    }

}