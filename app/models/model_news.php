<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */

class Model_News extends Model {
    
    function __construct($table = 'news') {
        parent::__construct($table);
    }
    
    
    /**
     * Возвращает список страниц с количеством символов контента. Вернет все поля, если не указывать второй параметр
     * @param array $wh field=>value признаки, по которым выбрать список страниц
     * @param array $fields (не обязательно) Список возвращаемых полей
     * @param string $order (не обязательно) ORDER BY id DESC
     * @return array
     */
    public function getNewsRows($wh, $fields=FALSE, $order=false) {
        $sql = "";
        $rows = $this->getRows($wh, $fields, $order);
        if(!in_array('content', $fields)){
            return $rows;
        }
        return $this->getStatRows($rows);
    }
    
    
    /**
     * Возвращает страницу с количеством символов контента. Вернет все поля, если не указывать второй параметр
     * @param array $wh field=>value признаки, по которым выбрать список страниц
     * @param array $fields (не обязательно) Список возвращаемых полей
     * @return array
     */
    public function getNewsRow($wh, $fields=FALSE) {
        $row = $this->getData($wh, $fields);
        if(!in_array('content', $fields)){
            return $row;
        }
        return $this->getStatRows(array($row))[0];
    }
    
    
    /**
     * Возвращает список страниц с пересчетом количества символов контента, описания, ключевиков
     * @param array $rows массив страниц с контентом
     * @return array
     */
    public function getStatRows($rows) {
        foreach ($rows as $row) {
            $row['count']['content'] = mb_strlen($row['content'], 'utf-8');
            $row['count']['description'] = mb_strlen($row['description'], 'utf-8');
            $row['count']['keywords'] = mb_strlen($row['keywords'], 'utf-8');
            $rows_[] = $row;
        }
        return $rows_;
    }
    
    
    /**
     * Возвращает статистику по количеству (всего, активных...)
     * @return array
     */
    public function getStatCounts() {
        $ch = $this->initCache();
        $file = 'page_rates_count';
        $row = $ch->setTimelive(3600)->getArray($file);
        
        if(!$row){
            $row['all'] = $this->getCount('');
            $row['empty_content'] = $this->getCount(array('content'=>''));
            $row['empty_meta'] = $this->getCount(array('description'=>'', 'keywords'=>''));
            $ch->setArray($file, $row);
        }
        
        return $row;
        
    }
}