<?php

/**
 * Формирует кнопки постраничной навигации
 * @author fedornabilkin icq: 445537491
 */
class Pagination {

    
    /**
     * Шаблоны
     */
    public $classLink = 'page-link';
    public $classItem = 'page-item';
    
    public $linkTemplate = '<a class="{class-link}" href="{url}">{label}</a>';
    public $previousLinkTemplate = '<a class="{class-link}" href="{url}" aria-label="Previous">{label}</a>';
    public $nextLinkTemplate = '<a class="{class-link}" href="{url}" aria-label="Next">{label}</a>';
    
    public $itemTemplate = '<li class="{class-item}">{link}</li>';
    public $activeItemTemplate = '<li class="{class-item} active">{link}</li>';
    
    /**
     * Данные
     */
    private $limit;
    private $all;
    private $current_page;
    private $url;

    /**
     * Массив ссылок
     */
    public $links = array();
    
    
    /**
     * @param integer $count Количество всех страниц
     * @return object $this
     */
    public function setAll($count) {
        $this->all = $count;
        return $this;
    }
    
    
    /**
     * Возвращает количество элементов, которые необходимо пропустить в выборке из БД
     * 
     * Если отображено 100 элементов на странице и находимся на 4 странице,
     * то из БД получаем записи с 300 по 400 (выбрать с (4-1)*100 до 100)
     * @return object $this
     */
    public function getOffset() {
        return ($this->current_page - 1) * $this->limit;
    }
    
    
    /**
     * @param integer $count Количество элементов на странице
     * @return object $this
     */
    public function setLimit($count) {
        $this->limit = $count;
        return $this;
    }
    
    
    /**
     * @param integer $num Номер текущей страницы
     * @return object $this
     */
    public function setCurrentPage($num) {
        $this->current_page = $num;
        return $this;
    }
    
    
    /**
     * @param string $url Часть ссылки без пагинатора или REQUEST_URI
     * @param boolean $parse true - разбирает переданный в первом параметре REQUEST_URI, добавляет номер страницы в конец
     * @example setUrl('/pages/all/4', true) OR setUrl('/pages/chpu?page=')
     * @return object $this
     */
    public function setUrl($url, $parse = false) {
        if($parse){
            $partsurl = explode('/', $url);
            
            if($this->current_page > 0){
                unset($partsurl[array_search(end($partsurl), $partsurl)]);
            }
            $this->url = implode('/', $partsurl) . '/';
        }else{
            $this->url = $url;
        }
        return $this;
    }

    
    /**
     * Добавляет очередную ссылку
     * @param array $link array('label'=>'Sites', 'url'=>'/sites.php');
     * @return object
     */
    public function setLink($link) {
        if(is_array($link)){
            $this->links[] = $link;
        }
        return $this;
    }

    /**
     * Добавляет сразу несколько ссылок
     * @param array $links
     * @return object
     */
    public function setLinks($links) {
        if(is_array($links)){
            foreach ($links as $val) {
                $this->setLink($val);
            }
        }
        return $this;
    }
    
    /**
     * Рендерит шаблоны ссылок
     * @return string
     */
    public function run() {
        if(count($this->links) < 1){
            $this->pager();
        }
        
        foreach ($this->links as $link) {
            if (!is_array($link)) {
                $link = array('label' => $link);
            }
            $links[] = $this->renderItem($link, !$link['active'] ? $this->itemTemplate : $this->activeItemTemplate);
        }
        
        return implode('', $links);
    }
    
    
    /**
     * Пересчет количества страниц, формирование массива ссылок
     * 
     * @return object $this
     */
    public function pager($prev = 3) {
        // осуществляем проверку, чтобы выводимые первая и последняя страницы не вышли за границы нумерации
        $this->current_page = ($this->current_page < 1) ? 1 : $this->current_page;
        $first = $this->current_page - $prev;
        if ($first < 1) {
            $first = 1;
        }
        $last = $this->current_page + $prev;
        if ($last > ceil($this->all / $this->limit)) {
            $last = ceil($this->all / $this->limit);
        }

        // начало вывода нумерации, выводим первую страницу
        $y = 1;
        if ($first > 1) {
            $this->setLink(array('label'=>1, 'url'=> $this->url.$y));
        }
        // Если текущая страница далеко от 1-й (> $prev * 2), то часть предыдущих страниц скрываем
        // Если текущая страница имеет номер до $prev * 2, то выводим все номера
        $y = $first - 1;
        if ($first > ($prev * 2)) {
            $this->setLink(array('label'=>'...', 'url'=>$this->url.$y));
        } else {
            for ($i = 2; $i < $first; $i++) {
                $this->setLink(array('label'=>$i, 'url'=>$this->url.$i));
            }
        }
        // отображаем заданный диапазон: текущая страница +-$prev
        for ($i = $first; $i < $last + 1; $i++) {
            // если выводится текущая страница, то ей назначается особый стиль css
            if ($i == $this->current_page) {
                $this->setLink(array('label'=>$i, 'url'=>$this->url.$i, 'active'=>true));
            } else {
                if ($i != 1) {
                    $this->setLink(array('label'=>$i, 'url'=>$this->url.$i));
                }
                else{
                    $this->setLink(array('label'=>$i, 'url'=>$this->url.$i));
                }
            }
        }
        $y = $last + 1;
        // часть страниц скрываем троеточием
        if ($last < ceil($this->all / $this->limit) && ceil($this->all / $this->limit) - $last > 2) {
            $this->setLink(array('label'=>'...', 'url'=>$this->url.$y));
        }
        // выводим последнюю страницу
        $e = ceil($this->all / $this->limit);
        if ($last < ceil($this->all / $this->limit)) {
            $this->setLink(array('label'=>$e, 'url'=>$this->url.$e));
        }

        return $this;
    }
    

    /**
     * @param array $link массив данных для ссылки 
     * @param $tpl шаблон элемента (активный или обычный)
     * @example array('label'=>'Sites', 'url'=>'/sites.php');
     * @return string 
     */
    private function renderItem($link, $tpl) {
        $label = (!$link['url']) ? $link['label']: $this->renderLink($link);
        return strtr($tpl, array('{link}' => $label, '{class-item}' => $this->classItem));
    }

    /**
     * Возвращает html тэг сылки <a href="{url}">{label}</a>
     * @param array $link массив данных для ссылки
     * @example array('label'=>'Sites', 'url'=>'/sites.php');
     * @return string
     */
    private function renderLink($link) {
        if(!$link['url']){
            return false;
        }
        if(!$link['label']){
            $link['label'] = $link['url'];
        }
        return strtr($this->linkTemplate, array('{url}' => $link['url'], '{label}' => $link['label'], '{class-link}' => $this->classLink));
    }

}
