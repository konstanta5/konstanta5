<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */

class Model_Comments extends Model {
    
    function __construct($table) {
        parent::__construct($table);
    }
    
    
    /**
     * Возвращает данные из БД по id. Вернет id, если не указывать второй параметр
     * @param integer $id Идентификатор в БД
     * @param array $fields (не обязательно) Список возвращаемых полей
     * @return array
     */
    public function getCommentById($id, $fields = array('id')) {
        return $this->getData($id, $fields);
    }
    
    
    /**
     * Возвращает список. Вернет все поля, если не указывать второй параметр
     * @param array $wh field=>value признаки, по которым выбрать список строк
     * @param array $fields (не обязательно) Список возвращаемых полей
     * @return array
     */
    public function getComments($wh, $fields=FALSE, $order = false) {
        // собрать обычный запрос для вывода комментов, которые проверены
//        $this->get_where($wh);
//        $where = ($this->where) ? 'AND ': 'WHERE ';
//        $this->select = implode(", ", $fields);
//        $sql = "SELECT $this->select FROM $this->table $this->where $where `moderator` > 0 $order ";
//        //echo $sql;
//        return $this->super_query($sql, 1);
        return $this->getRows($wh, $fields, $order);
    }
    
    
    /**
     * Возвращает список.
     * @param array $fields (не обязательно) Список возвращаемых полей array('id','comment','entity','entity_id','time')
     * @return array
     */
    public function getNewComments($fields = array('id')) {
        return $this->getRows(array('moderator'=>'0'), $fields, 'ORDER BY time DESC');
    }
}