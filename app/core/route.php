<?php

/**
 * @autor fedornabilkin icq: 445537491
 */
class Route {

    /**
     * router parts
     */
    static $routes;
    static $controller;
    static $action;
    static $uri;

    static function start() {
        Timer::start('Route');
//        self::$routes = explode('/', $_SERVER['REQUEST_URI']);
        self::$uri = getenv('REQUEST_URI');
        $uri_pars = parse_url(self::sanitization(self::$uri));
        self::$routes = self::$routes = explode('/', strtolower($uri_pars['path']));

        // получаем имя контроллера
        self::$routes[1] = self::$controller = (!empty(self::$routes[1])) ? self::$routes[1] : 'main';
        // получаем имя экшена
        self::$routes[2] = self::$action = (!empty(self::$routes[2]) ) ? self::$routes[2] : 'index';

        if (self::$routes[1] !== 'main') {
            Timer::start('checkChpu');
            self::checkChpu();
            Timer::stop('checkChpu',7);
        }

        // добавляем префиксы
        $controller = 'Controller_' . self::$controller;
        $action = 'action_' . self::$action;

        // если нет файла, то выдаем 404
        if (!file_exists(APP_DIR . "/controllers/" . strtolower($controller) . '.php')) {
            $controller = 'Controller_404';
        }
        Timer::stop('Route',7);
        
        // создаем контроллер
        $controller = new $controller;

        ////////////////
        if (method_exists($controller, $action)) {
            // вызываем действие контроллера
            $controller->$action()->action(); // и метод из контроллера ядра
        } else {
            $controller->errorPage();
            $controller->action();
        }
        exit();
    }

    /**
     * @return void
     */
    private static function checkChpu() {
        
//        $old = explode('/', self::$uri);
//        unset($old[0]);
        //self::historyChpu(implode('/', $old));
        
        foreach (App::param('chpu') as $val) {
            // если наш ЧПУ
            if (self::$controller == $val['chpu']) {
                self::$routes[1] = self::$controller = $val['ctrl'];
                if($val['action']){
                    self::$routes[2] = self::$action = $val['action'];
                }
                return;
            }
 
        }
        
        if(self::findPage()){
            return;
        }
        
        // если контроллер, то не ищем ЧПУ в БД
        $ctrls = scandir(APP_DIR . '/controllers');
        foreach ($ctrls as $ctrl) {
            $name = explode('_', $ctrl);
            if(self::$controller == substr(end($name), 0, -4)){
                return;
            }
        }
        
//        // костыль для ЧПУ обменников
//        if (self::findExchange()) {
//            return;
//        }
//        // костыль для ЧПУ валют
//        elseif (self::findCurrency()) {
//            return;
//        }
//        // костыль для ЧПУ курсов обмена
//        elseif (self::findRatePage()) {
//            return;
//        }
//        // костыль для ЧПУ страниц
//        elseif (self::findInfoPage()) {
//            return;
//        }
    }

    /**
     * Поиск Страницы
     * @return boolean
     */
    private static function findPage() {
        $actions = array('add','edit');
        if(self::$controller != 'pages' or !self::$routes[2]){
            return false;
        }
        
        if(in_array(self::$routes[2], $actions)){
            return false;
        }
        
        $model = new Model_Pages();
        $res = $model->getPageById(self::$routes[2]);

        if ($res['id'] > 0) {
            self::$routes[3] = self::$routes[2];
            self::$routes[1] = self::$controller = 'pages';
            self::$routes[2] = self::$action = 'read';
            return true;
        }
        return false;
    }


    /**
     * Постоянное перенаправление 301 редирект
     * @param string $url 
     * @return void
     */
    static function redirect301($url) {
        header($_SERVER['SERVER_PROTOCOL'] . " 301 Moved Permanently");
        header("Location: /" . $url);
        exit();
    }


    /**
     * Переадресовывает на новый урл, если старый устарел и записана история
     * @param string $old Старый ЧПУ
     * @return string
     */
    static function historyChpu($old) {
        
        $model = new Data('history_chpu');
        $row = $model->getData(array('old'=>$old), array('new'), 'ORDER BY id DESC');
        if($row){
            //header("X-history: chpu");
            self::redirect301($row['new']);
        }
    }


    /**
     * Удаляет теги, крайние пробелы и экранирует кавычки
     * @param string $str 
     * @return string
     */
    static function sanitization($str) {
        // удаление хтмл и пхп тегов
        $str = htmlspecialchars( strip_tags( trim( $str ) ) ); 
        // удаляем экранирование, если включены "Волшебные кавычки"
        if( get_magic_quotes_gpc () ){
            $str = stripslashes ($str);
        }
        return addslashes($str); // экранируем кавычку
    }

}
