<?php namespace Common;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Common global functions
 */

/**
 * Load (include) some file
 */
function load($filename,$directory='_core',$type='php')
{
    $file=APPPATH.$directory.DIRECTORY_SEPARATOR.$filename.'.'.$type;
    if (is_file($file) && is_readable($file)){
	require $file;
    } else {
	show_error('Unable to load the specified file: '.$file);
	exit;
    }
}

/**
 * Load some CLASS by name (also filename)
 * 
 * This function acts as a singleton.  If the requested class does not
 * exist it is instantiated and set to a static variable.  If it has
 * previously been instantiated the variable is returned.
 * 
 * @return object
 */
function &load_class($class,$directory='_core',$prefix='')
{
    $arr=explode('\\',$class);
    $classFile=array_pop($arr);
    $filepath=$directory.DIRECTORY_SEPARATOR.$classFile.'.php';
    $className=$prefix.$class;
    
    static $_classes = array();
    
    if (isset($_classes[$className]))
    {
	return $_classes[$className];
    }
    $found=null;
    foreach (array(APPPATH, BASEPATH) as $path)
    {
	if (file_exists($path.$filepath))
	{
	    if (class_exists($class) === FALSE)
	    {
		if ( ! require($path.$filepath))
		{
		    show_error("Unable to locate the specified class: {$class}.php",
				"Unable load file");
		    exit;
		}
		else {
		    $found=true;
		}
	    }
	}
    }
    if (is_null($found)) {
	    show_error("Unable to locate the specified class: {$class}.php",
				"No file {$path}{$filepath}");
	    exit;
    }
    if ( ! class_exists($className)) {
	show_error("Unable to locate the specified class: {$class}.php",
		    "Class not exist in file.");
	exit;
    }
    $_classes[$className] = new $className();
    return $_classes[$className];
}
/**
 * Load CONFIGURE file
 * 
 * This function acts as a singleton.  If the requested file does not
 * exist it is instantiated and set to a static variable.  If it has
 * previously been instantiated the variable is returned.
 */
function &get_config($configFile='config')
{
    $configName=$configFile;
    static $configs=array();
    if (isset($configs[$configName]))
    {
	return $configs[$configName];
    }
    
    $filepath=APPPATH.'config/'.$configFile.'.php';
    // Fetch the config file
    if ( ! file_exists($filepath))
    {
	show_error('The configuration file does not exist.');
	exit;
    }
    
    $configs[$configName]=require $filepath;
    
    // Does the $config array exist in the file?
    if ( ! isset($configs[$configName]) OR ! is_array($configs[$configName]))
    {
	show_error('Your config file does not appear to be formatted correctly.');
	exit;
    }
    
    return $configs[$configName];
}

function modprobe($module)
{
    if (!extension_loaded($module))
    {
	if (!dl($module.'.so'))
	{
	    throw new Exception("Could not load module {$module}");
	}
	else
	{
	    return 2; //success load
	}
    }
    else
	return 1; //allready loaded
}



function check_php_version($min)
{
    if (function_exists('version_compare'))
    {
	if (version_compare(PHP_VERSION, $min, '>='))
	{
	    return true;
	}
	else {
	    throw new Exception('PHP version error! Version of PHP is less then "'.$min.'"');
	}
    }
    else {
	throw new Exception('PHP version error! version_compare function not exist.');
    }
}

function add_include_path($path)
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
	    throw new Exception('Could not add new include path!');
	}
    }
    return true;
}

/**
 * ERRORS HANDLE SECTION
 */

/**
 * PHP errors catch
 */
function _exception_error_handler($level, $message, $filepath, $line){
    if ( ! DEBUG){
	// Если рабочая среда - лесом стрикты. Они не очень-то нужны.
	if ($level == E_STRICT){
	    return;
	}
    }
    ob_start();
    include(APPPATH.'errors/error_php.php');
    $buffer = ob_get_contents();
    ob_end_clean();
    echo $buffer; 
}

/**
 * For FATAL ERRORS. 
 * 
 * In fact, this function will be called every time, when script will
 * shutdown!
 */
function _exception_fatalError_handler(){
    $error = @error_get_last();
    if (is_array($error)){
	_exception_error_handler($error['type'],
				 $error['message'],
				 $error['file'],
				 $error['line']
	);
    }
}

function show_error($title,$message='No message',$code=500,$templ='error_general')
{
    set_status_header($code);
    $message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';
    ob_start();
    include(APPPATH.'errors/'.$templ.'.php');
    $buffer = ob_get_contents();
    ob_end_clean();
    
    echo $buffer;
    exit;
}

function show_404(){
    set_status_header(404);
    include(APPPATH.'errors/error_404.php');
    exit;
}

/**
 * Set HTTP header status code
 */
function set_status_header($code = 200, $text = null)
{
	$statuses = array(
		    200	=> 'OK',
		    201	=> 'Created',
		    202	=> 'Accepted',
		    203	=> 'Non-Authoritative Information',
		    204	=> 'No Content',
		    205	=> 'Reset Content',
		    206	=> 'Partial Content',

		    300	=> 'Multiple Choices',
		    301	=> 'Moved Permanently',
		    302	=> 'Found',
		    304	=> 'Not Modified',
		    305	=> 'Use Proxy',
		    307	=> 'Temporary Redirect',

		    400	=> 'Bad Request',
		    401	=> 'Unauthorized',
		    403	=> 'Forbidden',
		    404	=> 'Not Found',
		    405	=> 'Method Not Allowed',
		    406	=> 'Not Acceptable',
		    407	=> 'Proxy Authentication Required',
		    408	=> 'Request Timeout',
		    409	=> 'Conflict',
		    410	=> 'Gone',
		    411	=> 'Length Required',
		    412	=> 'Precondition Failed',
		    413	=> 'Request Entity Too Large',
		    414	=> 'Request-URI Too Long',
		    415	=> 'Unsupported Media Type',
		    416	=> 'Requested Range Not Satisfiable',
		    417	=> 'Expectation Failed',

		    500	=> 'Internal Server Error',
		    501	=> 'Not Implemented',
		    502	=> 'Bad Gateway',
		    503	=> 'Service Unavailable',
		    504	=> 'Gateway Timeout',
		    505	=> 'HTTP Version Not Supported'
	    );

	if (isset($statuses[$code]) AND ! $text)
	{
	    $text = $statuses[$code];
	} elseif ( ! isset($statuses[$code])) {
	    exit('Unable set status header');
	}

	$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

	if (substr(php_sapi_name(), 0, 3) == 'cgi')
	{
	    header("Status: {$code} {$text}", TRUE);
	}
	elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
	{
	    header($server_protocol." {$code} {$text}", TRUE, $code);
	}
	else
	{
	    header("HTTP/1.1 {$code} {$text}", TRUE, $code);
	}
}
