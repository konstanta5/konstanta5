<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */

class Controller_Contacts extends Controller {
    
            
    function __construct() {
        parent::__construct();
    }
    
    
    public function action_index() {
        
        if($this->getPost()){
//            if(!$this->getPost('email')){
//                $error = 'Укажите электронный адрес. ';
//                $this->content['error'] = true;
//            }
            if(!$this->getPost('message')){
                $error .= 'Добавьте текст сообщения.';
                $this->content['error'] = true;
            }
            if($this->content['error']){
                $this->session->addFlash(array('alert' => $error));
                return $this;
            }
            
            $to = App::param('support_mail');
            $sbj = 'Сообщение с сайта '.App::param('site_name');
            $name = $this->getPost('name');
            $email = $this->getPost('email');
            $msg = $this->getPost('message');
            
            // собираем письмо
            $subject = 'Сообщение с сайта '.App::param('home_url').'';
            $body = 'Пользователь ' .$name. ' написал следующее сообщение:'
                    . "\n-----\n".$msg."\n-----\n"
                    . 'Отправлено ' .date('d.m.y в H:i:s', time());
            
            // отправка письма
            $m = new Mailer();
            $m->setTo(App::param('support_mail'))->setFrom(App::param('support_mail'))->setName(App::param('site_name'))
                ->setSubject($subject)->setBody($body);
            
            if($email){
                $m->setReplyTo($email);
            }
            $m->send();
            // перенаправление
            $this->session->addFlash(array('alert' => 'Сообщение успешно отправлено.'));
            $this->goBack();
        }
        
        $this->content['row'] = $this->getPost();
        $this->setPageContent();
        
        $this->content['title'] = 'Контактная информация';
        return $this;
    }
}