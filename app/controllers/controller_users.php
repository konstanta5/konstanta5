<?php

/**
 * @autor fedornabilkin icq: 445537491
 */
class Controller_Users extends Controller {

    /**
     * Содержит объект для работы с таблицей
     */
    public $model;
    /**
     * Айди юзера
     */
    private $uid;

    function __construct() {
        $this->model = new Model_Users('users');
        $this->uid = Route::$routes[3];
        parent::__construct();
    }

    function action_index() {
        $this->content['title'] = 'Пользователи';
        $this->content['rows'] = $this->model->getRows('', array('id','login','mail','status','last_login','time'), 'ORDER BY time DESC');
        return $this;
    }

    function action_login() {
        if (App::$user->id) {
            $this->goHome();
        }

        $this->content['title'] = 'Авторизация пользователя';
        if ($this->getPost()) {
            $fields = array('id', 'login', 'salt', 'password', 'status');
            $login = $this->getPost('login');
            $user = $this->model->findByLogin($login, $fields);
            if ($user['id'] && $this->model->validatePassword($this->getPost('password'), $user['salt'], $user['password'])) {
                unset($user['salt'], $user['password']);
                $this->session->session_set(array('user' => $user));
                $this->goBack();
            } else {
                $this->content['alert'] = 'Логин или пароль указаны неверно.';
                $this->content['error'] = true;
            }
        }
        return $this;
    }

    function action_logout() {
        if (App::$user->id) {
            $this->session->destroy();
        }
        $this->goHome();
    }

    /**
     * @see validateUnique()
     * @see setPassword()
     */
    function action_registration() {
        if (App::$user->id) {$this->goHome();}

        $this->content['title'] = 'Регистрация пользователя';
        if ($this->getPost()) {

            if (!$this->validateUnique()) {
                return $this;
            }

            $this->setPassword();
            $this->setPost(array('time'=>time()));
            $uid = $this->model->exceptFields($this->getPost())->save();
            if ($uid) {
                $this->session->session_set(array('user' => array('id' => $uid, 'login'=>$this->getPost('login'))));
                $this->session->addFlash(array('alert' => 'Регистрация прошла успешно. На указанный адрес отправлено письмо с инструкцией.'));
                // send mail
                $this->registrationMail();
                $this->goHome();
            } else {
                $this->session->addFlash(array('alert' => 'Ошибка регистрации. Обратитесь в поддержку.'));
                $this->content['error'] = true;
            }
        }// post
        return $this;
    }

    function action_passrec() {
        if (App::$user->id) {$this->goHome();}

        $this->content['title'] = 'Восстановление пароля';
        if ($this->getPost()) {
            $user = $this->model->findByLoginMail($this->getPost(), array('id', 'login', 'mail'));
            if (!$user['id']) {
                $this->session->addFlash(array('alert' => 'Пользователь не найден.'));
                $this->content['error'] = true;
                return $this;
            }
            // secret key
            $params = array('secret_key' => $this->model->generateSecretKey());
            $up = $this->model->exceptFields($params)->save($user['id']);

            if ($up) {
                // send mail
                $this->passrecMail($params['secret_key']);
                $this->session->addFlash(array('alert' => 'На электронный адрес, указанный при регистрации, отправлено письмо с инструкциями по восстановлению пароля.'));
                $this->goHome();
            }
        }
        return $this;
    }

    function action_newpassword() {
        if (App::$user->id) {
            $this->goHome();
        }
        $key = Route::$routes[3];
        $this->content['title'] = 'Установка нового пароля';

        if (!$this->model->validateExpireSecretKey($key)) {
            $this->session->addFlash(array('error' => true, 'alert' => 'Время действия ссылки истекло, необходимо получить новую ссылку.'));
            $this->redirect('/users/passrec');
        }
        $user = $this->model->findBySecretKey($key);
        if ($this->getPost() && $user['id']) {
            $this->setPassword();
            $this->setPost(array('secret_key' => ''));
            $up = $this->model->exceptFields($this->getPost())->save($user['id']);
            if ($up) {
                $this->session->session_set(array('user' => array('id' => $user['id'])));
                $this->session->addFlash(array('alert' => 'Новый пароль успешно установлен.'));
                $this->goHome();
            }

            $this->session->addFlash(array('alert' => 'Ошибка! Невозможно изменить пароль, обратитесь в поддержку.'));
        }
        $this->content['row'] = $this->getPost();
        return $this;
    }

    function action_profile() {
        if (!App::$user->id) {
            $this->goHome();
        }

        $this->content['title'] = 'Личный кабинет';
        $fields = array('avatar', 'mail', 'salt', 'password');
        $this->content['row'] = $this->model->findById(App::$user->id, $fields);


        if ($this->getPost()) {
            // new mail
            if (!$this->validateNewMail()) {
                return $this;
            }

            // new password
            if ($this->getPost('password') && ($this->getPost('newpassword') == $this->getPost('newpassword_repeat') && $this->getPost('newpassword') != '')) {
                if ($this->model->validatePassword($this->getPost('password'), $this->content['row']['salt'], $this->content['row']['password'])) {
                    $this->setPost(array('password' => $this->getPost('newpassword')));
                    $this->setPassword();
                } else {
                    $this->session->addFlash(array('alert' => 'Неправильно указан текущий пароль'));
                    $this->content['error'] = true;
                    return $this;
                }
            } else {
                $this->removePost('password');
            }

            // update
            $up = $this->model->exceptFields($this->getPost())->save(App::$user->id);
            if ($up) {
                $this->session->addFlash(array('alert' => 'Данные успешно обновлены'));
                $this->redirect('/users/profile');
            }
        }

        return $this;
    }
    
    
    public function action_status() {
        // чтобы случайно не забрать статус у себя
        if(App::$user->id == $this->uid){
            $this->session->addFlash(array('alert' => 'Нельзя присвоить статус себе'));
            $this->content['error'] = true;
            return $this;
        }
        if(in_array(App::$user->status, array('admin','moderator'))){
            $up = $this->model->exceptFields($this->getPost())->save($this->uid);
            $this->session->addFlash(array('alert' => 'Статус присвоен'));
        }
        else{
            $this->session->addFlash(array('alert' => 'Недостаточно прав'));
            $this->content['error'] = true;
        }
        return $this;
    }

    /**
     * Генерация соли и хэша для пароля нового пользователя.
     * @return void
     */
    public function setPassword() {
        $salt = $this->model->getRandomString();
        $this->setPost(array('salt' => $salt, 'password' => $this->model->encodePassword($this->getPost('password'), $salt)));
    }

    /**
     * Проверка на уникальность (login, mail), возвращает true, если логин и email свободны
     * @return boolean
     */
    public function validateUnique() {
        if (!$this->getPost('login') or !$this->getPost('mail')) {
            $this->session->addFlash(array('alert' => 'Необходимо указать логин и электронный адрес'));
            $this->content['error'] = true;
            return false;
        }
        $user = $this->model->findByLoginMail($this->getPost(), array('login', 'mail'));
        if (!$user) {
            return true;
        }
        if ($user['login'] == $this->getPost('login')) {
            $this->session->addFlash(array('alert' => 'Этот логин уже занят'));
        } elseif ($user['mail'] == $this->getPost('mail')) {
            $this->session->addFlash(array('alert' => 'Этот email уже занят'));
        }elseif (!$this->getPost('login')) {
            $this->session->addFlash(array('alert' => 'Необходимо указать логин'));
        }elseif (!$this->getPost('mail')) {
            $this->session->addFlash(array('alert' => 'Необходимо указать email'));
        }
        $this->content['row'] = $this->getPost();
        $this->content['error'] = true;
        return false;
    }


    /**
     * Проверка нового email адреса на уникальность
     * @return boolean
     */
    public function validateNewMail() {
        if ($this->content['row']['mail'] != $this->getPost('mail')) {
            if (!$this->validateUnique()) {
                $this->session->addFlash(array('alert' => 'Этот адрес email уже используется'));
                $this->content['error'] = true;
                return $this;
            }
            $this->setPost(array('mail_approve' => 0));
        }
        return true;
    }

    /**
     * Отправка мыла при регистрации
     * @return boolean
     */
    private function registrationMail() {
        $m = new Mailer();
        $m->setTo($this->getPost('mail'))
                ->setReplyTo(App::param('support_mail'))
                ->setFrom(App::param('support_mail'))
                ->setSubject('Регистрация на сайте '.App::param('http_home_url').'')
                ->setBody('Вы зарегистрировались на сайте '.App::param('http_home_url').'. Ваш логин: '.$this->getPost('login').'.')
                ->send();
    }

    /**
     * Отправка мыла при восстановлении
     * @param string $key Секретный ключ для изменения пароля
     * @return boolean
     */
    private function passrecMail($key) {
        $m = new Mailer();
        $url_recovery = '' . App::param('http_home_url') .'/users/newpassword/'. $key;
        $body = "Здравствуйте.\nВы активировали сброс пароля на сайте " . App::param('http_home_url') .". "
                . "Чтобы установить новый пароль, пройдите по ссылке $url_recovery \n"
                . "Если вы не запрашивали сброс пароля на сайте " . App::param('http_home_url') .", просто проигнорируйте это письмо.";
        $m->setTo($this->getPost('mail'))
                ->setReplyTo(App::param('support_mail'))
                ->setFrom(App::param('support_mail'))
                ->setSubject('Восстановление доступа на сайте '.App::param('http_home_url').'')
                ->setBody($body)
                ->send();
    }

    /**
     * Сброс пароля для пользователя вручную
     * @return void
     */
//    public function resetPassword() {
//        $salt = $this->model->getRandomString();
//        $this->setPost(array('salt'=>$salt));
//        $this->setPost(array('password'=>  $this->model->encodePassword($this->getPost('password'), $salt)));
//    }
}
