<?php

/**
 * Формирует и выполняет запрос в БД
 * @autor: fedornabilkin icq: 445537491
 */
class Data extends Mysql {

    
    /**
     * array
     */
    public static $tables;
    
    /**
     * array
     */
    public static $index;
    
    //public $id = 0;
    public $table;
    public $error = false;
    public $fields;
    public $set = "";
    
    protected $primary;
    
    /**
     * string
     */
    protected $select = "*";
    protected $order;
    protected $limit;
    
    private $table_fields = array();

    function __construct($table) {
        if(!self::$tables){
            self::$tables = $this->super_query("SHOW tables ;", 1);
        }
        foreach (self::$tables as $row) {
            if (in_array($table, $row)) {
                $this->table = $table;
                break;
            }
        }
        if(!$this->table){
            exit('No table name: ' .$table);
        }
        parent::__construct();
    }

    /**
     * Формируем строку LIMIT *
     * @param array|integer $limit array(0,100) or int 100
     * @return object $this
     */
    public function setLimit($limit = array(0,100)){
        if(is_array($limit) && count($limit) == 2){
            $this->limit = 'LIMIT '.$limit[0].','.$limit[1];
        }elseif(is_int($limit)){
            $this->limit = 'LIMIT '.$limit;
        }
        return $this;
    }


    /**
     * Возвращает название поля первичного ключа таблицы
     * @return string
     */
    protected function get_primary() {
        if(!self::$index[$this->table]){
            $primary = $this->super_query("SHOW INDEX FROM `$this->table` ;");
            self::$index[$this->table] = $primary['Column_name'];
        }
        return self::$index[$this->table];
    }

    /**
     * Возвращает массив с названиями полей в таблице
     * @return array
     */
    protected function getFields() {
        if(is_array($this->table_fields[$this->table])){
            return $this->table_fields[$this->table];
        }
        $query = $this->query("SHOW COLUMNS FROM `$this->table` ;");
        if ($this->num_rows($query) > 0) {
            $fields = array();
            while ($row = $this->get_row($query)) {
                $fields[] = $row['Field'];
//                $fields[] = '`' . $row['Field'] . '`';
            }
            $this->table_fields[$this->table] = $fields;
        }
        return $fields;
    }

    /**
     * @param integer|array $wh primary id or array('field' => 'val') - WHERE field = 'val'
     * @return string
     * @see field_val()
     */
    public function get_where($wh) {
        if (!$wh) {
            $wher = '';
        } elseif (is_array($wh) && count($wh) > 0) {
            $wher = "WHERE " . $this->field_val($wh, "AND");
        } elseif (!is_array($wh)) {
            $wher = "WHERE " . $this->get_primary() . "= $wh ";
        }
        $this->where = $wher;
        return $wher;
    }

    /**
     * @return string
     */
    public function field_val($par, $sep, $op = false) {
        $op = ($op) ? $op : '=';
        foreach ($par as $field => $val) {
            $divader = (!$set) ? '': $sep;
            $set .= " $divader $field"."$op '$val' ";
//            if (!$set){
//                $set = " $field $op '$val' ";
//            }
//            else{
//                $set .= " $sep $field $op '$val' ";
//            }
        }
        return $set;
    }

    
    /**
     * @deprecated
     * @see getData()
     * @see getRows()
     */
    function get_data($wh, $params = false) {
        return $this->getData($wh, $params);
    }
    /**
     * @param integer|array $wh id or array('field' => 'value')
     * @param array $params Список полей, значения которых необходимо вернуть
     * @return array
     */
    function getData($wh, $params = false){
        $this->get_where($wh);
        $fields = ( is_array($params) && count($params) > 0 ) ? $params : $this->getFields();
        $this->select = implode(", ", $fields);
        return $this->super_query("SELECT $this->select FROM $this->table $this->where LIMIT 1 ;");
    }

    
    /**
     * @param integer|array $wh id or array('field' => 'value')
     * @param array $params Список полей, значения которых необходимо вернуть
     * @param string $order Условие сортировки ORDER BY `id` DESC
     * @see setLimit()
     * @return array
     */
    function getRows($wh, $params = false, $order = false) {
        $this->get_where($wh);
        $this->order = (!$order) ? "" : $order;
        
        $fields = ( is_array($params) && count($params) > 0 ) ? $params : $this->getFields();
        $this->select = implode(", ", $fields);
        
        $sql = "SELECT $this->select FROM $this->table $this->where $this->order $this->limit ;";
        return $this->super_query($sql, 1);
    }

    
    /**
     * @param array $wh array('field' => 'value')
     * @return integer Количество строк
     */
    function getCount($wh) {
        $this->get_where($wh);        
        
        $sql = "SELECT count(*) as count FROM $this->table $this->where ;";
        $query = $this->super_query($sql);
        return $query['count'];
    }

    /**
     * @deprecated
     * @see insert()
     */
    function insert_row($params) {
        return $this->insert($params);
    }
    /**
     * Возвращает id новой записи
     * @param array $params Массив поле->значение
     * @return integer 
     */
    function insert($params) {
        $set = "SET" . $this->field_val($params, ",");
        $this->query("INSERT INTO $this->table $set ;");
        return $this->insert_id();
    }

    /**
     * @deprecated
     * @see edit()
     */
    function edit_row($wh, $par) {
        return $this->edit($wh, $par);
    }
    /**
     * @param integer|array $wh id or array('field' => 'value')
     * @param array $par Список полей с новыми значениями поле->значение
     * @return boolean
     */
    function edit($wh, $par) {
        $this->get_where($wh);
        $set = "SET " . $this->field_val($par, ",");
        $sql = "UPDATE $this->table $set $this->where ;";
        return $this->query($sql);
    }
    
    
    /**
     * Удаляет строки из БД
     * @param integer|array $wh id or array('author' => 'Петя')
     * @return boolean
     */
    function delete($wh) {
        $this->get_where($wh);
        $sql = "DELETE FROM $this->table $this->where ;";
        return $this->query($sql);
    }

}
