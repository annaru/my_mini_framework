<?php
/**
 * APPLICATION FOLDER NAME
 */
$application_folder = "app";

/**
 * Error reporting level
 */
error_reporting(E_ALL);

/**
 * APPLICATION CONSTANTS
 */
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', rtrim(dirname(__FILE__),DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR);
define('APPPATH', BASEPATH.$application_folder.DIRECTORY_SEPARATOR);
define('PHP_MIN_ALLOW', '5.3.0');
/**
 * DEBUG/DEVELOPMENT MODE ON/OFF
 * 
 * If debug is on: all levels of errors
 * and all custom output in code will be displayed.
 * 
 * @flag bool
 * @default false
 */
define('DEBUG', 1);
//ini_set('display_errors',0);

/**
 * Check is the app path valid.
 */
if (! defined('APPPATH') || ! is_dir(APPPATH)){
    exit('Your application folder path does not appear to be set correctly.');
}

// And away we go.
require APPPATH.'_core/Init.php';
