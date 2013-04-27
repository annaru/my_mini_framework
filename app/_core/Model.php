<?php namespace Core;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Model {
    var $DB;
    var $dbconf;
    /**
     * В текущей версии поддерживаются только mysql
     * @todo универсализировать _init_PDO_connection
     */
    var $supported_drivers = array('mysql');
    
    public function __construct($configName)
    {
	$this->dbconf =& \Common\get_config('db');
	$this->_init_PDO_connection($configName);
    }
    
    function _init_PDO_connection($configName='default')
    {
	if ( ! class_exists('\PDO'))
	{
	    \Common\show_error('PDO error','You don\'t have PDO Extension');
	    exit;
	}
	if (! isset($this->dbconf[$configName]) ||
	    ! is_array($this->dbconf[$configName]))
	{
	    \Common\show_error("Unable get config '{$configName}' for database
				connection.");
	    exit;
	} else {
	    $cfg = $this->dbconf[$configName];
	}
	
	if ( ! in_array($cfg['driver'], $this->supported_drivers))
	{
	    \Common\show_error('PDO error', "Unsupported db driver {$cfg['driver']}");
	    exit;
	}
	
	$cfg['char_set'] = (isset($cfg['char_set']) && $cfg['char_set']) ?
	    $cfg['char_set'] : 'utf8';
	/**
	 * Collect data For connection
	 */
	$dsn = sprintf('%s:host=%s;port=%d;dbname=%s',
			$cfg['driver'],
			$cfg['hostname'],
			$cfg['port'],
			$cfg['database']
		    );
	// Prior to version 5.3.6, charset was ignored.
	$options = array(
	    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$cfg['char_set'],
	);
	
	/**
	 * Try create connection with PDO
	 */
	try
	{
	    $this->DB = new \PDO($dsn,
				 $cfg['username'],
				 $cfg['password'],
				 $options
				 );
	    	    
	    $this->DB->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	    
	} catch (\PDOException $e) {
	    \Common\show_error('Database error',$e);
	} catch (\Exception $e) {
	    \Common\show_error('Database error',$e);
	}
    }
    
    
    /*
     * WRAPPER FUNCTIONS
     */
     
    function _get($table){
	try 
	{ 
	    $sql="SELECT * FROM user_groups";
	    $res = $this->DB->prepare($sql)->execute();
	    $res=$this->DB->query($sql);
	    
	    
	} catch (\PDOException $e) {
	    \Common\show_error('PDO error', $e);
	    exit;
	}
	if ( ! $res instanceof \PDOStatement){
	    \Common\show_error('PDO error', print_r($this->DB->errorInfo(),true));
	}
	return $res->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}
