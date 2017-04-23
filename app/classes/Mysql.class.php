<?php

/*
 * @autor icq: 445537491
 */

if (!defined('SCRIPT')) {
    die("Статья 272. Неправомерный доступ к компьютерной информации (MySQL)!");
}

class Mysql {

    public static $query_list = array();
    
    public $show_error = false;
    public $query_num = 0;
    public $mysql_error = '';
    public $mysql_version = '';
    public $mysql_error_num = 0;
    public $mysql_extend = "MySQL";
    public $MySQL_time_taken = 0;
    
    private $query_id = false;
    private $db_id = false;
    private $connected = false;

    function __construct() {
        $this->show_error = DBSHOW_ERROR;
    }
    
    
    /**
     * @return boolean
     */
    function connect($user, $pass, $name, $location = 'localhost') {
        if (!$this->db_id = @mysql_connect($location, $user, $pass)) {
            if ($this->show_error) {
                $this->display_error(mysql_error(), mysql_errno());
            }
            return false;
        }

        if (!@mysql_select_db($name, $this->db_id)) {
            if ($this->show_error) {
                $this->display_error(mysql_error(), mysql_errno());
            }
            return false;
        }

        if (!defined('COLLATE')) { define("COLLATE", "cp1251");}


        $this->mysql_version = mysql_get_server_info();
        if (version_compare($this->mysql_version, '4.1', ">=")){
            mysql_query("/*!40101 SET NAMES '" . COLLATE . "' */");
        }

        return $this->connected = true;
    }

    
    /**
     * @param string $query SQL query
     * @return resource
     */
    function query($query) {
        $time_before = $this->get_real_time();

        if (!$this->connected){
            $this->connect(DBUSER, DBPASS, DBNAME, DBHOST);
        }

        if (!($this->query_id = mysql_query($query, $this->db_id) )) {

            $this->mysql_error = mysql_error();
            $this->mysql_error_num = mysql_errno();

            if ($this->show_error) {
                $this->display_error($this->mysql_error, $this->mysql_error_num, $query);
            }
        }

        $this->MySQL_time_taken += $this->get_real_time() - $time_before;


        self::$query_list[] = array(
            'sql' => $query,
            'time'  => ($this->get_real_time() - $time_before),
            'num'   => (count(self::$query_list) + 1));

        $this->query_num ++;

        return $this->query_id;
    }

    
    /**
     * @return array
     */
    function get_row($query_id = '') {
        $query_id = ($query_id) ? $query_id: $this->query_id;
        return mysql_fetch_assoc($query_id);
    }

    function get_array($query_id = '') {
        $query_id = ($query_id) ? $query_id: $this->query_id;
        return mysql_fetch_array($query_id);
    }

    
    /**
     * @param string $query SQL
     * @param boolean $multi false - return row (default), true - return rows
     * @return array row|rows
     */
    function super_query($query, $multi = false) {
        $this->query($query);
        
        if (!$multi) {
            $data = $this->get_row();
        }
        else {
            $data = array();
            while ($row = $this->get_row()) {
                $data[] = $row;
            }

        }
        $this->free();
        return $data;
    }

    
    /**
     * @return integer
     */
    function num_rows($query_id = '') {
        $query_id = ($query_id) ? $query_id: $this->query_id;
        return mysql_num_rows($query_id);
    }

    
    /**
     * @return integer
     */
    function insert_id() {
        return mysql_insert_id($this->db_id);
    }

    
    /**
     * @return array
     */
    function get_result_fields($query_id = '') {
        $query_id = ($query_id) ? $query_id: $this->query_id;

        while ($field = mysql_fetch_field($query_id)) {
            $fields[] = $field;
        }

        return $fields;
    }

    
    /**
     * escape
     * @return mixed
     */
    function safesql($source) {
        if ($this->db_id){
            return mysql_real_escape_string($source, $this->db_id);
        }
        else{
            return mysql_escape_string($source);
        }
    }

    
    /**
     * @return void
     */
    function free($query_id = '') {
        if ($query_id == ''){
            $query_id = $this->query_id;
        }
        @mysql_free_result($query_id);
    }

    
    /**
     * @return void
     */
    function close() {
        @mysql_close($this->db_id);
    }

    
    /**
     * @return float
     */
    function get_real_time() {
        list($seconds, $microSeconds) = explode(' ', microtime());
        return ((float) $seconds + (float) $microSeconds);
    }

    
    
    function display_error($error, $error_num, $query = '') {
        if ($query) {
            // Safify query
            $query = preg_replace("/([0-9a-f]){32}/", "********************************", $query); // Hides all hashes
            $query_str = "$query";
        }

        echo '<?xml version="1.0" encoding="iso-8859-1"?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<title>MySQL Fatal Error</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
		<style type="text/css">
		<!--
		body {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 10px;
			font-style: normal;
			color: #000000;
		}
		-->
		</style>
		</head>
		<body>
			<font size="4">MySQL Error!</font> 
			<br />------------------------<br />
			<br />
			
			<u>The Error returned was:</u> 
			<br />
				<strong>' . $error . '</strong>

			<br /><br />
			</strong><u>Error Number:</u> 
			<br />
				<strong>' . $error_num . '</strong>
			<br />
				<br />
			
			<textarea name="" rows="10" cols="52" wrap="virtual">' . $query_str . '</textarea><br />

		</body>
		</html>';

        exit();
    }

}