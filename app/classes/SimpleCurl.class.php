<?php

/**
 * @author fedornabilkin icq: 445537491
 *
 * Работает просто:
 *
 * 1. Создаем объект и скармливаем ссылку, по которой хотим получить/отправить данные.
 * $curl = new SimpleCurl("http://google.ru");
 * 
 * 2. Устанавливаем необходимые параметры
 * $curl->setPost($post); // данные пост-запроса array('login'=>'user', 'password'=>'12345')
 * $curl->setCookieFile('/cookie.txt'); // Файл для сохранения куки
 * $curl->saveCookie(); // Сохранить куки
 * $curl->getCookie(); // Получить куки
 * $curl->setHeaders($headers); // Установить заголовки
 * $curl->setTimeoutConnect(); // Максимальное время на вполнение запроса по умолчанию 0 (неограничено)
 * $curl->setTimeout(); // Макисмальное время выполнения курл-функций (30)
 * $curl->setBuffer($size); // Установить размер буфера
 * $curl->setWriteFunction($fname); // Установить функцию для записи данных 
 * $curl->setProxy($proxy); // Использование прокси array('ip'=>'238.238.238.238', 'port'=>'1245', 'username'=>'user', 'password'=>'', 'type'=>'socks5')
 * $curl->noProgress(); // Включает индикатор прогресса, если передать false
 * $curl->checkSsl(); // false - запрещает проверку SSL-сертификата
 * $curl->saveFile($file); // Путь к файлу для записи данных
 * $curl->followLocation($maxredir); // Следовать перенаправлениям. По умолчанию 5 редиректов
 * 
 * 3. Выполняем запрос
 * $response = $curl->exec();
 * 
 * 4. Смотрим информацию о запросе или ошибки
 * echo $curl->getQueryInfo(); // Возвращает информацию о запросе
 * echo $curl->getError(); // Возвращает ошибки
 * 
 * $curl->getResponseHeaders(); // Возвращает заголовки
 * 
 * 
 * Подробнее http://php.net/curl-setopt
 */

/*
   // Выполнить этот код и получить заголовки своего браузера
  $headers = '';
  foreach ($_SERVER as $key => $value) {
  if (strpos($key, 'HTTP_') === 0 && $key != 'HTTP_HOST' && $key != 'HTTP_CONNECTION') {
  $key = strtolower(strtr(substr($key, 5), '_', '-'));
  //echo $key . ': ' . $value . "<br />";
  $headers_str .= $key . ': ' . $value . "\r\n";
  $headers_arr[$key] = $value;
  }
  }
  //


 */
class SimpleCurl {

    private $curl;
    private $file;
    private $cookie_file = null;
    private $info = null;
    private $headers = null;
    private $error = array();
    private static $callback_headers_data;

    
    function __construct($url) {
        if (isset($url)) {
            $this->curl = curl_init(strval($url));
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, 'SimpleCurl::callbackHeaders');
        } else{
            $this->error = array('construct' => "Некорректный или отсутствует адрес запроса.");
        }
        return $this;
    }

    /**
     * Закрывает соединение, освобождает ресурсы
     * @return void
     */
    public function __destruct() {
        $this->close();
    }

    /**
     * Выполняет запрос и возвращает полученные данные
     * @param boolean $close (не обязательно) Закрывает соединение
     * @return string
     */
    public function exec($close = true) {
        
        $exec = curl_exec($this->curl);
        $this->info = curl_getinfo($this->curl);
        $this->headers = self::$callback_headers_data;
        self::$callback_headers_data = array();

        if ($exec === false){
            $this->error[] = array('exec' => "Ответ не получен (exec).");
        }
        if ($close){
            $this->close();
        }

        return $exec;
    }

    /**
     * Callback-функция
     * @return integer
     */
    function callbackHeaders($curl, $headers) {
        $string = preg_replace('#[\r\n]#Uis', '', $headers);

        if ($string != '') {
            $string = explode(': ', $string, 2);
            if (isset($string[1])) {
                self::$callback_headers_data[$string[0]] = $string[1];
            } else {
                self::$callback_headers_data[] = $string[0];
            }
        }

        return strlen($headers);
    }

    /**
     * @param array $post данные пост-запроса
     * @return object $this
     */
    public function setPost($post) {
        if (is_array($post) && count($post) > 0) {
            curl_setopt($this->curl, CURLOPT_POST, TRUE);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);
        } else{
            $this->error[] = array('post' => "Данные POST отсутствуют.");
        }
        return $this;
    }

    /**
     * @param string $file Путь к файлу
     * @return object $this
     */
    public function setCookieFile($file) {
        $this->cookie_file = $file;
        if(!is_file($this->cookie_file)){
            $fp=fopen($this->cookie_file,'w');
            fclose($fp);
            chmod($this->cookie_file, 0666);
        }
        
        if(!is_file($this->cookie_file)){
            $this->error[] = array('setCookieFile' => "Не указан (некорректный) путь к файлу cookie!");
        }
        return $this;
    }

    /**
     * Сохранять куки
     * @return object $this
     */
    public function saveCookie() {
        if (is_file($this->cookie_file)){
            curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
        }
        elseif(!is_file($this->cookie_file)){
            $this->error[] = array('saveCookie' => "Не указан (некорректный) путь к файлу cookie!");
        }
        return $this;
    }

    /** 
     * Получать куки
     * @return object $this
     */
    public function getCookie() {
        if (is_file($this->cookie_file)){
            curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
        }
        elseif(!is_file($this->cookie_file)){
            $this->error[] = array('getCookie' => "Не указан (некорректный) путь к файлу cookie!");
        }
        return $this;
    }

    /**
     * @param array $headers Массив с заголовками
     * @return object $this
     */
    public function setHeaders($headers) {
        if (is_array($headers) && count($headers) > 0) {
            $_headers = array();

            foreach ($headers as $value) {
                $value = explode(':', $value, 2);
                $_headers[$value[0]] = $value[1];
            }
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $_headers);
        } else{
            $this->error[] = array('setHeaders' => "Данные headers отсутствуют.");
        }
        return $this;
    }
    
    /**
     * Добавляет заголовки в ответ
     * @return object $this
     */
    public function setHeadersResponse() {
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        return $this;
    }

    /**
     * Время ожидания при попытке соединения. 0 - неограничено
     * @param integer $sec (не обязательно) Время в секундах
     * @return object $this
     */
    public function setTimeoutConnect($sec = 0) {
        if ($sec >= 0){
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $sec);
        }
        else{
            $this->error[] = array('setTimeoutConnect' => "Некорректное значение времени ожидания.");
        }
        return $this;
    }

    /**
     * Максимальное время выполнения cURL-функций
     * @param integer $sec (не обязательно) Время в секундах
     * @return object $this
     */
    public function setTimeout($sec = 30) {
        if ($sec > 0){
            curl_setopt($this->curl, CURLOPT_TIMEOUT, $sec);
        }
        else{
            $this->error[] = array('setTimeout' => "Некорректное значение времени выполнения.");
        }
        return $this;
    }

    /** 
     * Размер буфера
     * @param integer $size Размер буфера в байтах
     * @return object $this
     */
    public function setBuffer($size) {
        if ($size > 0){
            curl_setopt($this->curl, CURLOPT_BUFFERSIZE, $size);
        }
        else{
            $this->error[] = array('setBuffer' => "Некорректное значение Размера буфера.");
        }
        return $this;
    }

    /**
     * Устанавливает функцию для записи данных
     * @param string $fname Имя функции
     * @return object $this
     */
    public function setWriteFunction($fname) {
        if ($fname != '' or function_exists($fname)){
            curl_setopt($this->curl, CURLOPT_WRITEFUNCTION, $fname);
        }
        else{
            $this->error[] = array('setWriteFunction' => "Функция не объявлена.");
        }
        return $this;
    }

    /**
     * Использование прокси. Если указать 'type'=>'socks5', то будет использован CURLPROXY_SOCKS5, иначе CURLPROXY_HTTP
     * @param array $proxy Массив с данными прокси
     * @example $proxy = array('ip'=>'238.238.238.238', 'port'=>'1245', 'username'=>'user', 'password'=>'', 'type'=>'socks5')
     * @return object $this
     */
    public function setProxy($proxy) {
        if ($proxy['ip'] && $proxy['port']) {
            if ($proxy['type']){
                $type_proxy = CURLPROXY_SOCKS5;
            }
            elseif (!$proxy['type']){
                $type_proxy = CURLPROXY_HTTP;
            }
            curl_setopt($this->curl, CURLOPT_PROXYTYPE, $type_proxy);
            curl_setopt($this->curl, CURLOPT_PROXY, $proxy['ip']);
            curl_setopt($this->curl, CURLOPT_PROXYPORT, $proxy['port']);

            if ($proxy['username'] != "" and $proxy['password'] != FALSE) {
                curl_setopt($this->curl, CURLOPT_PROXYUSERPWD, $proxy['username'] . ':' . $proxy['password']);
            }
        } else{
            $this->error[] = array('setProxy' => "Некорректные параметры прокси(ip, port).");
        }
        return $this;
    }

    /**
     * Отключает индикатор прогресса
     * @param boolean $bool (не обязательно) true - off, false - on
     * @return object $this
     */
    public function noProgress($bool = true) {
        curl_setopt($this->curl, CURLOPT_NOPROGRESS, $bool);
        return $this;
    }

    /**
     * Отключает проверку сертификата удаленного сервера
     * @param boolean $bool (не обязательно) true - on, false - off
     * @return object $this
     */
    public function checkSsl($bool = false) {
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, $bool);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, $bool);
        return $this;
    }

    /**
     * Включает следование в случае перенаправления
     * @param integer $maxredir Количество максимальных редиректов
     * @return object $this
     */
    public function followLocation($maxredir = 5) {
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
        return $this;
    }

    /**
     * Включает подробный отчет
     * @param boolean $bool true - для подробного отчета, если ответ 400 и более
     * @return object $this
     */
    public function setFailError($bool = false) {
        curl_setopt($this->curl, CURLOPT_FAILONERROR, $bool);
        return $this;
    }

    /**
     * Включает принудительное использование нового соединения
     * @param boolean $bool true - on
     * @return object $this
     */
    public function setFresh($bool = false) {
        curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, $bool);
        return $this;
    }

    /**
     * 
     * @param string $ip "46.229.169.166"
     * @return object $this
     */
    public function setInterface($ip) {
        curl_setopt($this->curl, CURLOPT_INTERFACE, $ip);
        return $this;
    }

    /**
     * Файл для записи результата
     * @param boolean $file путь к файлу
     * @return object $this
     */
    public function saveFile($file) {
        $fp=fopen($file,'w');
        fclose($fp);
        if(is_file($file)){
            curl_setopt($this->curl, CURLOPT_FILE, $this->file = fopen($file, 'w+b'));
        }elseif(!is_file($file)){
            $this->error[] = array('saveFile' => "Не указан (некорректный) путь к файлу сохранения результата.");
        }
        return $this;
    }

    /**
     * Заголовки ответа
     * @return array
     */
    public function getResponseHeaders() {
        return $this->headers;
    }

    /**
     * Информация о запросе
     * @return mixed
     */
    public function getQueryInfo() {
        return $this->info;
    }

    /**
     * Возвращает ошибки при установке параметров
     * @return mixed
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Завершение сеанса, освобождение ресурсов
     * @return void
     */
    public function close() {
        if (!is_null($this->curl)) {
            curl_close($this->curl);
            $this->curl = null;
        }

        if (!is_null($this->file)) {
            fclose($this->file);
            $this->file = null;
        }
    }

}