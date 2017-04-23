<?php

// Обработчик событий для классов
interface eventHandler {

    function reaction($obj);
}

// Клас описывающий сущность "Грабители банка"
class GangstersGroup {

    static private $instance = null;
    private $gangsters = array(); //члены банды
    private $dateHeist; //Дата следующего ограбления

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    // получить единственный екземпляр этого класса
    static public function getInstance() {
        if (self::$instance == null) {
            self::$instance = new GangstersGroup();
        }
        return self::$instance;
    }

    // получить дату ограбления
    public function getDateHeist() {
        return $this->dateHeist;
    }

    // Установить дату ограбления
    public function setDateHeist($date) {
        $this->dateHeist = $date;

        //Сообщить всем членам банды информацию
        $this->notifyGangsters();
    }

    // Посвящение в члены банды
    public function introduceToGangstersGroup($gangsters) {
        $this->gangsters[] = $gangsters;
    }

    //Сообщить всем  членам банды
    function notifyGangsters() {
        foreach ($this->gangsters as $gangster) {

            $gangster->reaction($this);
        }
    }

}

// Агент под прикрытием FBI
class Agent implements eventHandler {

    private $name = ""; //Имя агента

    public function __construct($name) {

        // Агент получает кодовое имя
        $this->name = $name;

        // Внедряется в банду
        GangstersGroup::getInstance()->introduceToGangstersGroup($this);
    }

    // Докладывает в штаб ФБР
    public function reaction($obj) {
        if ($obj instanceof GangstersGroup) {
            // Обновить информацию.
            print "<br/>...<br/>На связи агент " . $this->name . ": известна дата ограбления! " . $obj->getDateHeist();
        }
    }

}

// Грабитель
class Robber implements eventHandler {

    private $name = "";

    public function __construct($name) {

        // Грабитель получает имя
        $this->name = $name;

        // внедряется в банду
        GangstersGroup::getInstance()->introduceToGangstersGroup($this);
    }

    // Доложить
    public function reaction($obj) {
        if ($obj instanceof GangstersGroup) {
            // Обновить информацию.
            print "<br/>Внимание всем членам банды! <strong>" . $obj->getDateHeist() . "</strong> Мы идем на <u>ограбление</u>! ";
        }
    }

}

//Создаем классы интересующиеся деятельностью GangstersGroup
$robber = new Robber('Bob');
$scully = new Agent('Scully');
$malder = new Agent('Malder');

// Шло время, выполнялись другие  функции...
// И вот Дон Аль Капоне сообщил дату ограбления:
GangstersGroup::getInstance()->setDateHeist(date("d-m-Y 00:00"));
