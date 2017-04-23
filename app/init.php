<?php

/**
 * @autor: fedornabilkin icq: 445537491
 */


define('VERSION', 2.01);
define('SCRIPT', true);
define('APP_DIR', dirname(__FILE__));

// loadClass
class Load {
    public static $classes;
    private $name;
    private $_files;
    function __construct($class) {
        $this->name = $class;
    }
    
    public function requireFile() {
        
        $this->_files[] = APP_DIR . '/classes/' . $this->name . '.class.php';
        $this->_files[] = APP_DIR . '/controllers/' . strtolower($this->name) . '.php';
        $this->_files[] = APP_DIR . '/models/' . strtolower($this->name) . '.php';
        $this->_files[] = APP_DIR . '/core/' . strtolower($this->name) . '.php';
        
        foreach ($this->_files as $file) {
            if (is_file($file)) {
                self::$classes[] = $this->name;
                require_once $file;            
                break;
            }
        }
    }
    
}

spl_autoload_register('loadClass');
function loadClass($class) {
    $load = new Load($class);
    $load->requireFile();
}

// timer
Timer::run();

// config
Timer::start('Configs');
$data_files = array('config', 'db', 'local', 'access');
foreach ($data_files as $val) {
    $file = APP_DIR . '/config/' . $val . '.php';
    if (is_file($file)) {
        require_once $file;
    }
}

// db
define ("DBHOST", $cfg['host']); 
define ("DBNAME", $cfg['name']);
define ("DBUSER", $cfg['user']);
define ("DBPASS", $cfg['pass']);
define ("COLLATE", $cfg['charset']);
define ("DBSHOW_ERROR", $cfg['show_error']);

// check ajax
$cfg['content_type'] = (getenv('HTTP_X_REQUESTED_WITH')) ? 'json' : 'html';
header("Content-Type: text/" . $cfg['content_type'] . "; charset=UTF-8");

App::setParams($cfg);
Timer::stop('Configs',7);