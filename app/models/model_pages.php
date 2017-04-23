<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */

class Model_Pages extends Model {
    
    function __construct($table = 'pages') {
        parent::__construct($table);
    }
    
    
    /**
     * Возвращает данные страницы из БД по chpu. Вернет id, если не указывать второй параметр
     * @param array $chpu array('chpu' => 'obmen_wmz_na_wmr')
     * @param array $fields (не обязательно ) Список возвращаемых полей
     * @return array
     */
    public function getPageByChpu($chpu, $fields = array('id')) {
        $row = $this->getData(array('chpu' => $chpu), $fields);
        return $this->getStatRows(array($row))[0];
//        $row = $this->getStatRows(array($row));
//        return $row[0];
    }
    
    
    /**
     * Возвращает данные страницы из БД по id. Вернет id, если не указывать второй параметр
     * @param integer $id Идентификатор обменника в БД
     * @param array $fields (не обязательно) Список возвращаемых полей
     * @return array
     */
    public function getPageById($id, $fields = array('id')) {
        return $this->getData($id, $fields);
    }
    
    
    /**
     * Возвращает список страниц. Вернет все поля, если не указывать второй параметр
     * @param array $wh field=>value признаки, по которым выбрать список страниц
     * @param array $fields (не обязательно) Список возвращаемых полей
     * @return array
     * @deprecated
     */
    public function getPages($wh, $fields=FALSE) {
        return $this->getRows($wh, $fields);
    }
    
    
    /**
     * Возвращает список страниц с количеством символов контента. Вернет все поля, если не указывать второй параметр
     * @param array $wh field=>value признаки, по которым выбрать список страниц
     * @param array $fields (не обязательно) Список возвращаемых полей
     * @return array
     */
    public function getPagesRows($wh, $fields=FALSE) {
        $rows = $this->getRows($wh, $fields);
        return $this->getStatRows($rows);
    }
    
    
    /**
     * Возвращает список страниц с курсами обмена
     * @param array $fields (не обязательно) Список возвращаемых полей
     * @return array
     */
    public function getPagesRates($fields=FALSE) {
        $rows = $this->getRows(array('type'=>''), $fields);
        return $this->getStatRows($rows);
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
            $row['empty_content'] = $this->getCount(array('content'=>'', 'content_after'=>''));
            $row['empty_meta'] = $this->getCount(array('description'=>'', 'keywords'=>''));
            $ch->setArray($file, $row);
        }
        
        return $row;
        
    }
}