<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */

class Model_Categories extends Model {
    
    function __construct($table = 'categories') {
        parent::__construct($table);
    }
    
    
    /**
     * Возвращает id и rank слудующей категории от заданного rank
     * @param integer $rank
     * @return array
     */
    public function nextCategory($rank) {
        return $this->getData(array('rank <'=>$rank-1), array('id','rank'));
    }
    
    
    /**
     * Возвращает id и rank предудущей категории от заданного rank
     * @param integer $rank
     * @return array
     */
    public function prevCategory($rank) {
        return $this->getData(array('rank >'=>$rank+1), array('id','rank'));
    }
    
    
    /**
     * Возвращает максимальный ранк
     * @return integer
     */
    public function getMaxRank() {
        $sql = "SELECT MAX(rank) AS max FROM categories ;";
        $query = $this->super_query($sql);
        return $query['max'];
    }
    
}