<?php namespace App\Controllers;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends \Core\Controller{
    
    public function index($test='tetstat'){
	$this->db =& \Common\load_class('\App\Models\main','models');
	print_r($this->db->get_some());
	
	echo $this->twig->render('Hello {{ name }}!', array('name' => 'Fabien'));
    }
    
    public function oh(){
	echo "etwtwet";
    }
}
