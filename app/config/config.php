<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

return array (
    /**
    * URL to your app root. Typically this will be your base URL,
    * WITH a trailing slash:
    * 	http://example.com/
    */
    'base_url' => 'http://localhost/',
    
    /**
     * This lets you specify with a regular expression which characters 
     * are permitted within your URLs.  When someone tries to submit a 
     * URL with disallowed characters they will get a warning message.
     */
    'permitted_uri_chars' => 'a-z 0-9~%.:_\-',
    
    /**
     * This option allows you to add a suffix to all generated URLs
     */  
    'url_suffix' => '.html',
    
    /**
     * This route indicates which controller class should be loaded 
     * if the URI contains no data.
     */
    'default_controller' => 'welcome',
);

