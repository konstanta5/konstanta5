<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */
class Controller_Test extends Controller {

    /**
     * Содержит объект для работы с таблицей pages
     */
    public $model;

    function __construct() {
        parent::__construct();
        $this->access();
        $this->model = new Model_Pages('pages');
    }

    public function action_test() {
        $path = ROOT_DIR . '/img/entity';
        $imgs = scandir($path);
        foreach ($imgs as $img) {
            if($img == '.' or $img == '..'){
                continue;
            }
            chmod($path .'/'. $img, 0666);
        }
        
        exit();
    }

    /**
     * Выполняет запрос в БД (создание таблицы, индекса, поля)
     */
    public function action_query() {
        if ($this->getPost()) {
            $sql = explode("\n", $_POST['queries']);
            if (!is_array($sql) or count($sql) < 1) {
                $this->session->addFlash(array('alert' => 'Ошибка добавления!'));
                $this->content['error'] = true;
                return $this;
            }

            $db = new Mysql;
            foreach ($sql as $value) {
                if(!$value){
                    $this->content['queries'][] = array('sql'=> 'Query was empty');
                    continue;
                }
                $res = $db->query($value);
                $this->content['queries'][] = array('sql'=> $value, 'error'=>$db->mysql_error, 'result'=>$res);
            }
            $this->session->addFlash(array('queries' => $this->content['queries']));
            $this->reload();
        }
        $this->content['queries'] = $this->session->getFlash('queries');
        return $this;
    }

    /**
     * Выполняет запрос в БД (выборка данных)
     */
    public function action_sqlresult() {
        if ($this->getPost()) {
            $sql = $_POST['query'];
            if (!$sql) {
                $this->session->addFlash(array('alert' => 'Ошибка добавления!'));
                $this->content['error'] = true;
                return $this;
            }

            $db = new Mysql;
            $res = $db->super_query($sql, true);
            
            // explain
            if($this->getPost('explain')){
                $explain = $db->super_query('EXPLAIN '.$sql, true);
            }
            $this->session->addFlash(array('query' => array('sql'=> $sql, 'error'=>$db->mysql_error, 'time'=>$db->MySQL_time_taken, 'result'=>$res)) );
            $this->session->addFlash(array('explain' => $explain));
            $this->reload();
        }
        $this->content['query'] = $this->session->getFlash('query');
        $this->content['explain'] = $this->session->getFlash('explain');
        return $this;
    }

    public function action_phpinfo() {
        echo phpinfo();
        exit();
    }

    public function action_path() {
        echo get_include_path() . './';
        exit();
    }

    public function action_removecmment() {
        $this->model = new Model_Comments('comments');
        $this->model->query("DELETE FROM `comments` LIMIT 1 ;");
        return $this;
    }

    public function action_curl() {

        $headers = '';
//        foreach ($_SERVER as $key => $value) {
//            if (strpos($key, 'HTTP_') === 0 && $key != 'HTTP_HOST' && $key != 'HTTP_CONNECTION') {
//                $key = strtolower(strtr(substr($key, 5), '_', '-'));
//                //echo $key . ': ' . $value . "<br />";
//                $headers_str .= $key . ': ' . $value . "\r\n";
//                $headers[$key] = $value;
//            }
//        }

        $headers = array(
            'pragma' => 'no-cache',
            'cache-control' => 'no-cache',
            'accept' => 'application/json, text/javascript, */*; q=0.01',
            'origin' => 'http://pro-obmen.ru',
            'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36',
            'referer' => 'http://pro-obmen.ru/',
            'accept-encoding' => 'gzip, deflate',
            'accept-language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
        );

        $url = 'https://1wm.kz/exportxml.xml';
        $parts = parse_url($url);
        $cookie_file = $parts['host'];
        $curl = new SimpleCurl($url);
        $curl->setCookieFile(ROOT_DIR . '/temp/curl/' . $cookie_file . '.txt')
                ->saveCookie()->getCookie()
                ->setHeaders($headers)
                ->checkSsl(false)
                ->followLocation()
                ->setTimeoutConnect(10)
                ->setTimeout(30)
        //->saveFile(ROOT_DIR . '/temp/curl/last-result.txt')
        ;

        $response = $curl->exec();
        //$response = $curl->getError();
        //print_r($response);
        exit($response);

        return $this;
    }

    public function action_index() {

        return $this;

        // sql
        $sql[] = "CREATE TABLE IF NOT EXISTS `comments` (
          `id` int(10) unsigned NOT NULL,
          `comment` varchar(3000) NOT NULL,
          `author` varchar(20) NOT NULL,
          `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Плохой 2, хороший 1, нейтральный 0',
          `entity` varchar(15) NOT NULL COMMENT 'Имя сущности, для чего комментарий (валюта, обменник)',
          `entity_id` int(10) unsigned NOT NULL COMMENT 'Айди сущности, для которой добавлен комментарий',
          `moderator` int(10) unsigned NOT NULL DEFAULT '0',
          `time` int(10) unsigned NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

        $sql[] = "CREATE TABLE IF NOT EXISTS `currency` (
          `id` int(10) unsigned NOT NULL,
          `name` varchar(50) NOT NULL,
          `description` varchar(255) NOT NULL,
          `keywords` varchar(255) NOT NULL,
          `content` varchar(10000) NOT NULL,
          `home_url` varchar(50) NOT NULL,
          `icon` varchar(50) NOT NULL,
          `chpu` varchar(15) NOT NULL,
          `rank` int(10) unsigned NOT NULL,
          `views` int(10) unsigned NOT NULL,
          `comments` int(10) unsigned NOT NULL,
          `category` tinyint(3) unsigned NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

        $sql[] = "CREATE TABLE IF NOT EXISTS `exchange` (
          `id` int(5) unsigned NOT NULL,
          `name` varchar(255) NOT NULL,
          `url` varchar(255) NOT NULL,
          `icon` varchar(50) NOT NULL,
          `chpu` varchar(255) DEFAULT NULL,
          `export_url` varchar(255) NOT NULL,
          `export_type` varchar(15) NOT NULL,
          `active` smallint(1) unsigned NOT NULL DEFAULT '0',
          `description` varchar(5000) NOT NULL,
          `wmid` bigint(12) NOT NULL,
          `bl` int(5) unsigned NOT NULL DEFAULT '0',
          `ts` int(5) unsigned NOT NULL DEFAULT '0',
          `reserve` int(10) unsigned NOT NULL DEFAULT '0',
          `rates_count` int(10) unsigned NOT NULL DEFAULT '0',
          `comments` int(10) unsigned NOT NULL DEFAULT '0'
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

        $sql[] = "CREATE TABLE IF NOT EXISTS `pages` (
          `id` int(10) unsigned NOT NULL,
          `title` varchar(255) NOT NULL,
          `description` varchar(255) NOT NULL,
          `keywords` varchar(255) NOT NULL,
          `chpu` varchar(255) NOT NULL,
          `content` varchar(10000) NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

        $sql[] = "CREATE TABLE IF NOT EXISTS `rates` (
          `id` int(10) unsigned NOT NULL,
          `currency_from` varchar(15) NOT NULL,
          `currency_to` varchar(15) NOT NULL,
          `currency_in` float NOT NULL,
          `currency_out` float NOT NULL,
          `exchange` int(5) unsigned NOT NULL,
          `amount` float(9,2) unsigned NOT NULL,
          `url` varchar(255) NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

        $sql[] = "CREATE TABLE IF NOT EXISTS `users` (
          `id` int(11) unsigned NOT NULL,
          `login` varchar(20) NOT NULL,
          `password` varchar(32) NOT NULL,
          `salt` varchar(3) NOT NULL,
          `mail` varchar(30) NOT NULL,
          `avatar` varchar(30) NOT NULL,
          `status` varchar(30) NOT NULL,
          `secret_key` varchar(44) NOT NULL,
          `time` int(11) unsigned NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $sql[] = "ALTER TABLE `comments`
          ADD PRIMARY KEY (`id`);";

        $sql[] = "ALTER TABLE `currency`
          ADD PRIMARY KEY (`id`);";

        $sql[] = "ALTER TABLE `exchange`
          ADD PRIMARY KEY (`id`);";

        $sql[] = "ALTER TABLE `pages`
          ADD PRIMARY KEY (`id`);";

        $sql[] = "ALTER TABLE `rates`
          ADD PRIMARY KEY (`id`);";

        $sql[] = "ALTER TABLE `users`
          ADD PRIMARY KEY (`id`);";

        $sql[] = "ALTER TABLE `comments`
          MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;";

        $sql[] = "ALTER TABLE `currency`
          MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;";

        $sql[] = "ALTER TABLE `exchange`
          MODIFY `id` int(5) unsigned NOT NULL AUTO_INCREMENT;";

        $sql[] = "ALTER TABLE `pages`
          MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;";

        $sql[] = "ALTER TABLE `rates`
          MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;";

        $sql[] = "ALTER TABLE `users`
          MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;";

        foreach ($sql as $key => $value) {
            $res = $this->model->query($value);
        }

        return $this;
    }

    /**
     * Ограничивает доступ к страницам данного раздела
     */
    private function access() {
        if (!in_array(App::$user->login, App::param('develops'))) {
            $this->errorPage();
        }
    }

}
