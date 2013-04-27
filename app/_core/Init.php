<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * System Initialization File
 *
 * Loads the base classes and executes the request.
 *
 * @package	CodeIgniter
 */

/**
 * Version
 */
define('APP_VERSION','0.0.0');

/**
 * Include global functions
 */
require APPPATH.'_core/Common.php';

/**
 * Check php version.
 */
try {
    \Common\check_php_version(PHP_MIN_ALLOW);
} catch (Exception $e){
    die($e->getMessage());
}

/**
 * Define a custom error handler so we can log PHP errors
 */
if (function_exists('\Common\_exception_error_handler')){
    set_error_handler('\Common\_exception_error_handler');
} else {
    exit('Unable to set exception handler!');
}
register_shutdown_function('\Common\_exception_fatalError_handler');

/**
 * Set a liberal script execution time limit
 */
if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
{
    @set_time_limit(300);
}

/**
 * protect against possible exploits - there is no need to have so much variables
 */
if (isset($_REQUEST) && (count($_REQUEST) > 1000)) {
    die('possible exploit');
}

/**
 * just to be sure there was no import (registering) before here
 * we empty the global space (but avoid unsetting $variables_list
 * and $key in the foreach(), we still need them!)
 */
$variables_whitelist = array (
    //'GLOBALS',
    //'_REQUEST',
    //'_ENV',
    '_SERVER',
    '_GET',
    '_POST',
    '_FILES',
    '_COOKIE',
    '_SESSION',
    'variables_whitelist',
);
foreach (get_defined_vars() as $key => $value) {
    if (! in_array($key, $variables_whitelist)) {
        unset($$key);
    }
}
unset($key, $value, $variables_whitelist);

/**
 * Get config
 */
$config=\Common\get_config();

/**
 * LOAD CLASSES
 */
\Common\load('Controller');
\Common\load('Model');
\Common\load('Exceptions');

$RTR =& \Common\load_class('\Core\Router');
$URI =& \Common\load_class('\Core\URI');

$RTR->_set_routing();

$class  = '\App\Controllers\\'.$RTR->fetch_class();
$method = $RTR->fetch_method();

/** 
 * Load the local application controller
 * 
 * Note: The Router class automatically validates the controller path 
 * using the router->_validate_request(). If this include fails it 
 * means that the default controller in the Routes.php file is not 
 * resolving to something valid.
 */
if ( ! file_exists(APPPATH.'controllers/'.$RTR->fetch_directory().$RTR->fetch_class().'.php'))
{
    \Common\show_error('Unable to load your default controller. 
	Please make sure the controller specified in your Routes.php file is valid.');
    exit;
}
/**
 * Load specified controller class
 */
include(APPPATH.'controllers/'.$RTR->fetch_directory().$RTR->fetch_class().'.php');

/**
 * Create controller object
 */
if (! class_exists($class))
{
    \Common\show_error('Unable create instance of controller class');
    exit;
}
$APP = new $class();

if ( ! method_exists($APP,$method)){
    \Common\show_404();
    exit;
}
// Call the requested method.
// Any URI segments present (besides the class/function) will be passed to the method for convenience
call_user_func_array(array(&$APP, $method), array_slice($URI->rsegments, 2));

// Close connections, etc.
