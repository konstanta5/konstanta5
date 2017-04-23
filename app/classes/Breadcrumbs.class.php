<?php

/**
 * Генерирует хлебные крошки
 *
 * @author fedornabilkin icq: 445537491
 * $bc->home_link = array('label' => 'Pro-obmen.ru', 'url' => '/');
 * $brc_links[0] = array('label'=>'<span class="fa fa-envelope-o"></span> Почтовый ящик', 'url'=>'/mailbox.php');
 * $brc->setLinks($brc_links);
 */
class Breadcrumbs {
    
    /**
     * HomeLink
     */
    public $home_link = array();
    
    /**
     * Шаблоны
     * <a href="{url}" itemprop="url"><span itemprop="title">{label}</span></a>
     * <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">{link}</span><i class="fa fa-angle-double-right" aria-hidden="true"></i>
     */
    public $linkTemplate = '<a href="{url}" title="{label}" itemprop="url"><span itemprop="title">{label}</span></a>';
    public $itemTemplate = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">{link}</span><span class="chevron">»</span>';
    public $activeItemTemplate = '<span>{link}</span>';
//    public $linkTemplate = '<a href="{url}">{label}</a>';
//    public $itemTemplate = "<li>{link}</li>";
//    public $activeItemTemplate = '<li class="active">{link}</li>';

    /**
     * Массив остальных ссылок
     */
    private $links = array();

    /**
     * Добавляет первую ссылку
     * @param array $link array('label' => 'Pro-obmen.ru', 'url' => '/');
     */
    function __construct($link = false) {
        if(is_array($link) && count($link) > 0){
            $this->home_link = $link;
        }
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
     * Обрабатываем массив ссылок
     * @return string
     */
    public function run() {
        if (empty($this->links)) {
            $this->links[] = $this->home_link;
        }
        else{
            array_unshift($this->links, $this->home_link);
        }
        
        foreach ($this->links as $link) {
            if (!is_array($link)) {
                $link = array('label' => $link);
            }
            $links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
        }
        
        return implode('', $links);
    }

    /**
     * @param array $link массив данных для ссылки 
     * @param $tpl шаблон элемента (активный или обычный)
     * @example array('label'=>'Sites', 'url'=>'/sites.php');
     * @return string 
     */
    private function renderItem($link, $tpl) {
        $label = (!$link['url']) ? $link['label']: $this->renderLink($link);
        return strtr($tpl, array('{link}' => $label));
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
        return strtr($this->linkTemplate, array('{url}' => $link['url'], '{label}' => $link['label']));
    }
    
}
