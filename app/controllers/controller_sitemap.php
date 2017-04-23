<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */

class Controller_Sitemap extends Controller {
    
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
        
        $rows[] = $this->generatePages();
        $rows[] = $this->generateExchange();
        $rows[] = $this->generateCurrency();
        
        foreach ($rows as $row) {
            foreach ($row as $val) {
                if(!$val['content']){continue;}
               $url .= '<url><loc>' . App::param('http_home_url') . '/' .$val['chpu']. '</loc></url>'; 
            }
        }
        
        $sitemap = '<?xml version="1.0" encoding="utf-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $sitemap .= '<url><loc>' . App::param('http_home_url') . '/</loc></url>';
        $sitemap .= '<url><loc>' . App::param('http_home_url') . '/obmenniki</loc></url>';
        $sitemap .= '<url><loc>' . App::param('http_home_url') . '/pages</loc></url>';
        $sitemap .= '<url><loc>' . App::param('http_home_url') . '/currency</loc></url>';
        $sitemap .= $url;
        $sitemap .= '</urlset>';
        
        header("Content-type: text/xml");
        exit($sitemap);
    }
    
    
    public function action_robots() {
        // поиск строки в кэше
        $ch = new Datacache;
        $ch_file = 'robots';
        $str = $ch->getArray($ch_file);
        
        // если кэш пустой
        if(!$str){
            $rows[] = $this->generatePages();
            //$rows[] = $this->generateExchange();
            $rows[] = $this->generateCurrency();

            foreach ($rows as $row) {
                foreach ($row as $val) {
                    if($val['content']){continue;}
                    $str .= "Disallow: /".$val['chpu']."\n"; 
                }
            }
            
            // записать в кэш
            $ch->setArray($ch_file, $str);
        }
        
        $robots = "User-Agent: *\nAllow: /\nDisallow: /app\nDisallow: /users/\nDisallow: /link/\n";
        $robots .= $str;
        $robots .= "Host: pro-obmen.ru Sitemap: http://pro-obmen.ru/sitemap.xml";
        header("Content-type: text/plain");
        exit($robots);
    }
    
    
    /**
     * Возвращает массив строк из таблицы pages БД
     * @return array rows
     */
    public function generatePages() {
        $model = new Model_Pages('pages');
        return $model->getPages('', array('chpu','content'));
    }
    
    
    /**
     * Возвращает массив строк из таблицы exchange БД
     * @return array rows
     */
    public function generateExchange() {
        $model = new Model_Exchange('exchange');
        $array = $model->getExchanges('', array('chpu'));
        foreach ($array as $val) {
            if(!$val['chpu']){
                continue;
            }
            $rows[] = $val;
        }
        return $rows;
    }
    
    
    /**
     * Возвращает массив строк из таблицы currency БД
     * @return array rows
     */
    public function generateCurrency() {
        $model = new Model_Currency('currency');
        $array = $model->getCurrencies('', array('chpu','content'));
        foreach ($array as $val) {
            if(!$val['chpu']){
                continue;
            }
            $rows[] = $val;
        }
        return $rows;
    }
    
}