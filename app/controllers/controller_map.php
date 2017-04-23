<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */

class Controller_Map extends Controller {
    
    /**
     * Содержит объект для работы с таблицей pages
     */
    public $pages;
    
    /**
     * Содержит объект для работы с таблицей exchange
     */
    public $exchange;
    
    /**
     * Содержит объект для работы с таблицей currency
     */
    public $currency;
    
    
    function __construct() {
        parent::__construct();
    }
    
    
    public function action_index() {
        
        $this->content['title'] = 'Карта сайта';
        
//        $this->content['news'] = $this->generateNews();
//        $this->content['exchanges'] = $this->generateExchange();
//        $this->content['currencies'] = $this->generateCurrency();
        
        
        
        return $this;
    }
    
    
    public function action_sitemap() {
        
//        $rows['news'] = $this->generateNews();
//        $rows[] = $this->generateExchange();
//        $rows[] = $this->generateCurrency();
        
//        foreach ($rows as $key => $row) {
//            foreach ($row as $val) {
//                if(!$val['content']){continue;}
//               $url .= '<url><loc>' . App::param('http_home_url') . '/' .$key. '/read/' .$val['id']. '</loc></url>'; 
//            }
//        }
        
        $sitemap = '<?xml version="1.0" encoding="utf-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $sitemap .= '<url><loc>' . App::param('http_home_url') . '/</loc></url>';
        $sitemap .= $url;
        $sitemap .= '</urlset>';
        
        header("Content-type: text/xml");
        exit($sitemap);
    }
    
    
    public function action_robots() {
                
        $robots_disallow = '';
        $disallow = array('/app','/users','/search');
        foreach ($disallow as $item) {
            $robots_disallow .= "Disallow: " .$item. "\n";
        }
        
        $robots = "User-Agent: *\nAllow: /\n";
        $robots .= $robots_disallow;
        $robots .= $str;
		
        # По совету админов закрыл от этого бота из-за злой нагрузки
        $robots .= "User-agent: SemrushBot\n";
        $robots .= "Disallow: /\n";
        $robots .= "User-agent: SemrushBot-SA\n";
        $robots .= "Disallow: /\n";
		
        $robots .= "Host: " . App::param('home_url') . "\nSitemap: " . App::param('http_home_url') . "/sitemap.xml";
        header("Content-type: text/plain");
        
        exit($robots);
    }
    
    
    /**
     * Возвращает массив строк из таблицы pages БД
     * @return array rows
     */
    public function generateNews() {
        $model = new Model_News();
        return $model->getRows(array('hide !'=>1), array('id','content','title','anons'), 'ORDER BY id DESC');
    }
    
}