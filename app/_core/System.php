<?php namespace Core\System;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class for features and differences of running operating system and PHP.
 */
class Features
{
    static public function platform()
    {
	if (defined('PHP_OS'))
	{
	    return substr(strtolower(PHP_OS),0,3); 
	} 
	else 
	{
	    return 'non';
	}
    }
    
    /**
     * @param string $path Absolute path to some directory
     */
    static public function add_include_path($path)
    {
	if (!defined('PATH_SEPARATOR'))
	{
	    /**
	    * Unix use ":" and Win use ","
	    */
	    $delimetrSymbol = (self::platform()=='lin') ? ':' : 
				(self::platform()=='non') ? ':' : ',';
	}
	else
	{
	    if ( ! set_include_path(get_include_path().PATH_SEPARATOR.$path) )
	    {
		throw new \Exception('Could not add new include path!');
	    }
	}
	return true;
    }
    /**
     * @todo: User-specified minimum version
     */
    static public function check_php_version()
    {
	if (function_exists('version_compare'))
	{
	    if (version_compare(PHP_VERSION, '5.3.0', 'lt'))
	    {
		return true;
	    }
	    else {
		throw new \Exception('PHP version error! Version of PHP is less then 5.3.x');
	    }
	}
	else {
	    if (defined('PHP_MAJOR_VERSION')&&defined('PHP_MINOR_VERSION'))
	    {
		if (PHP_MAJOR_VERSION>=5)
		{
		    if ((PHP_MAJOR_VERSION==5&&PHP_MINOR_VERSION>=3)===false)
		    {
			throw new \Exception('PHP MINOR version error! Version of PHP is less then 5.3.x');
		    }
		} else
		{
		    throw new \Exception('PHP MAJOR version error! Version of PHP is less then 5.x.x');
		}
	    } else {
		return false;
	    }
	}
    }
    
    public function __toString(){
	return sprintf("%s (PHP %s ver. %s)", PHP_OS, PHP_SAPI, PHP_VERSION);
    }
    static public function toString(){
	return sprintf("%s (PHP %s ver. %s)", PHP_OS, PHP_SAPI, PHP_VERSION);
    }
}

?>
