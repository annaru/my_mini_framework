<?php namespace App\Models;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends \Core\Model {
    
    public function __construct(){
	parent::__construct('default'); //create $this->DB
    }
    
    public function get_some(){
	//$sql="";
	//$this->db->exec($sql);
	return $this->_get('user_groups');
    }
}
