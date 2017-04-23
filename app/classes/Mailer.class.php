<?php

/**
 * Отправка электронной почты 
 * Упрощенный вариант. Письмо будет сформировано в windows-1251 и отправлено на support@ipweb.ru
 * можно использовать для теста или в формах обратной связи
 * $m = new Mailer;
 *  $m->setSubject("Subject")->setBody("Body text")->send();
 * 
 * Более сложная отправка
 * $m->setCharset("utf-8")
 *  ->setTo("email@mail.ru")
 *  ->setReplyTo("no-reply@ipweb.ru")
 *  ->setName("Ipweb")
 *  ->setSubject("Subject")
 *  ->setBody("Body text", "html")
 * ->send();
 * 
 * @author fedornabilkin icq: 445537491
 */
class Mailer {
    
    private $to;
    private $subject;
    private $body;
    private $replyTo;
    private $from;
    private $name;
    private $contentType = 'text/plain';
    private $charset = 'utf-8';
    private $encoding = '8bit'; // 8bit or base64
    private $message_id = false;
    private $headers = '';
    private $files = array();

    
    
    /**
     * Отправка электронного письма
     * @return void
    */
    public function send() {
        if(!$this->message_id){
            $this->setMessageId();
        }
        
        $this->headers .= "Reply-To: $this->replyTo\r\n";
        $this->headers .= "Mime-Version: 1.0\r\n";
        $this->headers .= "Message-ID: <$this->message_id>\r\n";
        $this->headers .= "From: $this->name <$this->from>\r\n";
        
        if(is_array($this->files) && count($this->files) > 0){
            $bound = $this->setBound();
            $this->headers .= "Content-Type: multipart/mixed; boundary=\"$bound\"\r\n";
            $this->headers .= "This is a multi-part message in MIME format\r\n";
        }
        else{
            $this->headers .= "Content-Type: $this->contentType; charset=$this->charset\r\n";
        }
        $this->headers .= "Content-Transfer-Encoding: $this->encoding";
        
        
        if($this->encoding == 'base64'){
            $this->body = $this->getChunkBase($this->body);
        }
        return mail($this->to, $this->subject, $this->body, $this->get_headers());
    }
    
    /**
     * Устанавливает тип кодирования письма 8bit или base64
     * @param string $type
     * @return object
     */
    public function setEncoding($type) {
        $this->encoding = $type;
        return $this;
    }
    
    /**
     * Устанавливает адрес, накоторый отправляем письмо
     * @param string $email
     * @return object
     */
    public function setTo($email) {
        $this->to = $email;
        return $this;
    }
    
    /**
     * Устанавливает тему письма
     * @param string $subject
     * @return object
     */
    public function setSubject($subject) {
        $this->subject = '=?'.$this->charset.'?B?'.base64_encode($subject).'?=';
        return $this;
    }
    
    /**
     * Устанавливает тело письма и тип контента html/plain по умолчанию plain
     * @param string $body
     * @param string $type
     * @return object
     */
    public function setBody($body, $type = false) {
        if ($type) {
            $this->setContetType($type);
        }
        $this->body = $body;
        return $this;
    }
    
    /**
     * Устанавливает тип отправляемого письма
     * @param string $type
     * @return object
     */
    public function setContetType($type) {
        $this->contentType = $type;
        return $this;
    }
    
    /**
     * Устанавливает адрес, на который может быть получен ответ
     * @param string $email
     * @return object
     */
    public function setReplyTo($email) {
        $this->replyTo = $email;
        return $this;
    }
    
    /**
     * Устанавливает значение для From
     * @param string $email
     * @return object
     */
    public function setFrom($email) {
        $this->from = $email;
        return $this;
    }
    
    /**
     * Устанавливает имя отправителя
     * @param string $name
     * @return object
     */
    public function setName($name) {
        $this->name = '=?'.$this->charset.'?B?'.base64_encode($name).'?=';
        return $this;
    }
    
    /**
     * Устанавливает кодировку письма, по умолчанию Windows-1251 (utf-8)
     * @param string $charset
     * @return object
     */
    public function setCharset($charset) {
        $this->charset = $charset;
        return $this;
    }
    
    /**
     * Устанавливает Message-ID
     * @param string $id
     * @example Message-ID: <20161017185212.993455.19232.7222.15775@delivery1m.cmail.yandex.net>
     * @return object
     */
    public function setMessageId($id=false) {
        $id = ($id) ? $id : time();
        $parts = explode('@', $this->from);
        $this->message_id = $id. '@' .end($parts);
    }
    
    /**
     * Возвращает заголовки, собранные в строку
     * @return string
     */
    private function get_headers() {
        return $this->headers;
    }
    
    /**
     * Возвращает закодированныю строку
     * @param string $str
     * @return string
     */
    private function getChunkBase($str) {
        return chunk_split(base64_encode($str), 76);
    }
    
    /**
     * @return string Уникальный набор символов
     */
    private function setBound(){
        return md5(uniqid(time()));
    }
    
}


    

//class Send_mail {
// 
//        private $_params = array(
//        'email' => '',  
//        'from_name' => '',
//        'from_mail' => '',
//        'subject' => '',
//        'message' => '',
//        'files' => array(),
//        'charset' => 'utf-8',
//        'content_type' => 'plain',
//        'time_limit' => 30
//        );
// 
//   
//        private $_error = true;
//        private $_error_text = '<br><span style="color:#F00;">';
//       
//       
//       
//        public function __call($name, $param)
//        {      
//                if(!isset($this->_params[$name]))
//                {
//                        $this->_error_text .= 'Некорректный параметр! '.$name.'()<br>';
//                        $this->_error = false;
//                }
//               
//                else if(count($param) > 1)
//                {
//                        $this->_error_text .=  'Ожидается 1 параметр в '.$name.'()!<br>';
//                        $this->_error = false;
//                }
//                else
//                {
//                        $this->_params[$name] = isset($param[0]) ? $param[0] : '';
//                }
//               
//                return $this;
//        }
//       
// 
//        private function _error_email()
//        {
//                if(empty($this->_params['email']))
//                {
//                        $this->_error = false;
//                        $this->_error_text .= 'Не указан параметр: email()<br>';       
//                }
//               
//                if(empty($this->_params['from_mail']))
//                {
//                        $this->_error = false;
//                        $this->_error_text .= 'Не указан параметр: from_mail()<br>';   
//                }
//               
//                $this->_error_text .= '</span>';
//               
//                return $this->_error;
//        }
//       
//       
//        public function send()
//        {
//                if($this->_error_email() === false)
//                echo $this->_error_text;
//                else
//                $this->_send();
//        }
//       
//       
//        private function _send()
//        {        
//                $from_name = '=?'.$this->_params['charset'].'?B?'.base64_encode($this->_params['from_name']).'?=';
//                $subject = '=?'.$this->_params['charset'].'?B?'.base64_encode($this->_params['subject']).'?=';
//               
//                $header = "From: ".$from_name." <".$this->_params['from_mail'].">\r\n";
//                $header .= "Reply-To: ".$this->_params['from_mail']."\r\n";
//                $header .= "MIME-Version: 1.0\r\n";
// 
//        // Если есть прикреплённые файлы
//                if(!empty($this->_params['files']))
//                {
//                        if(!is_array($this->_params['files']))
//                        $this->_params['files'] = array($this->_params['files']);
//                       
//                        $bound = md5(uniqid(time())); // Разделитель
//                       
//                        $header .= "Content-Type: multipart/mixed; boundary=\"".$bound."\"\r\n";
//                        $header .= "This is a multi-part message in MIME format.\r\n";
//                       
//                        $message = "--".$bound."\r\n";
//                        $message .= "Content-Type: text/".$this->_params['content_type']."; charset=".$this->_params['charset']."\r\n";
//                        $message .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
// 
//                        $message .= $this->_params['message']."\r\n\r\n";
//                   
//                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
// 
//                        foreach($this->_params['files'] as $file_name)
//                        {
//                                $name = preg_replace('~.*([^/|\\\]+)$~U', '$1', $file_name);
//                                $name = iconv('cp1251', 'UTF-8', $name);
//                                $name = "=?".$this->_params['charset']."?B?".base64_encode($name)."?=";
//                               
//                                $message .= "--".$bound."\r\n";
//                                $message .= "Content-Type: ".finfo_file($finfo, $file_name)."; name=".$name."\r\n";
//                                $message .= "Content-Transfer-Encoding: base64\r\n";
//                                $message .= "Content-Disposition: attachment; filename=\"".$name."\"; size=".filesize($file_name).";\r\n\r\n";
//                                $message .= chunk_split(base64_encode(file_get_contents($file_name)))."\r\n";
//                        }
//                       
//                        $message .= $bound."--";       
//                }
//                else // Если нет файлов
//                {
//                    $header .= "Content-type: text/".$this->_params['content_type']."; charset=".$this->_params['charset']."\r\n";     
//                        $message = $this->_params['message'];
//                }
//         
//               
//                set_time_limit($this->_params['time_limit']);
//               
//                // Отправка сообщения  
//                if(is_array($this->_params['email']))
//                {
//                        foreach($this->_params['email'] as $email)
//                        @mail($email, $subject, $message, $header);
//                }
//                else
//                {
//                        @mail($this->_params['email'], $subject, $message, $header);
//                }      
//        }      
//}