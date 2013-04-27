<?php namespace Core;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Controller{
    protected $twig;
    
    public function __construct(){
	$this->twig=$this->_get_twig_instance();
    }
    
    //abstract public function index();
    
    private function _get_twig_instance(){
	try{
	    \Common\load('Twig/Autoloader','libs');
	    \Twig_Autoloader::register();
	    $loader = new \Twig_Loader_String();
	    return new \Twig_Environment($loader);
	}
	catch (\Twig_Error $e)
	{
	    \Common\show_error('Twig internal error');
	    exit;
	}
	catch (\Twig_Error_Runtime $e)
	{
	    \Common\show_error('Twig runtime error');
	    exit;
	}
	catch (\Exception $e)
	{
	    throw new Exceptions\Load('Can not load Twig template engine!');
	}
    }
}
