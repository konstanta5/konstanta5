<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */
class Controller_Search extends Controller {

    /**
     * Содержит объект для работы с таблицей
     */
    public $model;
    private $chpu;
    private $phrase;

    function __construct() {
        parent::__construct();
        $this->phrase = $this->getPost('search');
    }

    public function action_index() {
        return $this;
    }

    /**
     * Поиск страницы по названию и ЧПУ
     */
    public function action_page() {
        if ($this->phrase) {
            $model = new Model_Pages('pages');

            $sql = "SELECT `chpu`, `title` FROM `pages` WHERE `title` LIKE '%$this->phrase%' OR `chpu` LIKE '%$this->phrase%'";
            $rows = $model->super_query($sql, 1);
            foreach ($rows as $row) {
                $row['chpu'] = '/' . $row['chpu'];
                $this->content['rows'][] = $row;
            }
        }

        return $this;
    }

    /**
     * Поиск валюты по названию и ЧПУ
     */
    public function action_currency() {
        if ($this->phrase) {
            $model = new Model_Currency('currency');

            $sql = "SELECT `chpu`, `name` AS `title` FROM `currency` WHERE `name` LIKE '%$this->phrase%' OR `chpu` LIKE '%$this->phrase%'";
            $rows = $model->super_query($sql, 1);
            foreach ($rows as $row) {
                $row['chpu'] = '/' . $row['chpu'];
                $this->content['rows'][] = $row;
            }
        }

        return $this;
    }

    /**
     * Поиск валюты по названию и ЧПУ
     */
    public function action_exchange() {
        if ($this->phrase) {
            $model = new Model_Exchange('exchange');

            $sql = "SELECT `chpu`, `name` AS `title` FROM `exchange` WHERE `name` LIKE '%$this->phrase%' OR `chpu` LIKE '%$this->phrase%'";
            $rows = $model->super_query($sql, 1);
            foreach ($rows as $row) {
                $row['chpu'] = '/' . $row['chpu'];
                $this->content['rows'][] = $row;
            }
        }

        return $this;
    }

    /**
     * Поиск валюты по названию и ЧПУ
     */
    public function action_rates() {
        if ($this->phrase) {
            $model = new Model_Rates('rates');

            $sql = "SELECT `currency_from`, `currency_to` FROM `rates` WHERE `currency_from` LIKE '%$this->phrase%' OR `currency_to` LIKE '%$this->phrase%'";
            $rows = $model->super_query($sql, 1);
            $temp = $arr = array();
            foreach ($rows as $val) {
                $arr[] = strtolower($val['currency_from']);
                $arr[] = strtolower($val['currency_to']);
            }
            foreach ($arr as $row) {
                if (false !== strpos($row, $this->phrase) && !in_array($row, $temp)) {
                    $this->content['rows'][] = array('title' => $row);
                }
                $temp[] = $row;
            }
            unset($temp);
        }

        return $this;
    }

    /**
     * Поиск валюты по названию и ЧПУ
     */
    public function action_availablerates() {
        // cache

        $model = new Model_Rates('rates');

        $this->content['rows'] = $model->availableRates();

        return $this;
    }

    /**
     * Поиск ЧПУ
     */
    public function action_chpu() {
        if ($this->getPost()) {
            $this->session->addFlash(array('alert' => 'свободен'));
            $this->chpu = Route::$routes[3];

            // search init
            if ($this->searchPageChpu()) {
                $this->content['error'] = true;
            } elseif ($this->searchCurrencyChpu()) {
                $this->content['error'] = true;
            } elseif ($this->searchExchangeChpu()) {
                $this->content['error'] = true;
            }

            if ($this->content['error']) {
                $this->session->addFlash(array('alert' => 'занят'));
            }
        }

        return $this;
    }

    /**
     * Поиск страницы по ЧПУ
     */
    private function searchPageChpu() {
        $model = new Model_Pages('pages');
        $this->content['row'] = $model->getPageByChpu($this->chpu);
        if ($this->content['row'] && $this->getPost('id') != $this->content['row']['id']) {
            return true;
        }
        return false;
    }

    /**
     * Поиск валюты по ЧПУ
     */
    private function searchCurrencyChpu() {
        $model = new Model_Currency('currency');
        $this->content['row'] = $model->findCurrencyByChpu($this->chpu);
        if ($this->content['row'] && $this->getPost('id') != $this->content['row']['id']) {
            return true;
        }
        return false;
    }

    /**
     * Поиск обменника по ЧПУ
     */
    private function searchExchangeChpu() {
        $model = new Model_Exchange('exchange');
        $this->content['row'] = $model->findExchangeByChpu($this->chpu);
        if ($this->content['row'] && $this->getPost('id') != $this->content['row']['id']) {
            return true;
        }
        return false;
    }

}
