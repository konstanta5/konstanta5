<?php

/**
 * @author fedornabilkin icq: 445537491
 * 
 * @example $timer = new Timer(); 
 * @example All start/stop $timer->stop('name'); echo $timer->result['name']; * 
 * @example start/stop $timer->start('name'); $timer->stop('name'); echo $timer->result['name'];
 * 
 * 
 */
class Timer {

    public static $result = array();
    private static $flag = array();

    static function run() {
        self::$flag['start'] = self::time_fix();
    }

    public static function time_fix() {
        $mtime = explode(' ', microtime());
        return $mtime[1] + $mtime[0];
    }

    public static function start($f) {
        self::$flag[$f] = self::time_fix();
    }

    public static function stop($f=false, $r = 5) {
        $fl = (self::$flag[$f] && $f) ? $f : 'start';

        $stop = self::time_fix() - self::$flag[$fl];
        self::$result[$f] = round($stop, $r);
        return self::$result[$f];
    }
    
    /**
     * @param string $f Имя временной метки
     * @return array|float
     */
    public static function getResult() {
        self::stop('start');
        return self::$result;
    }

}
