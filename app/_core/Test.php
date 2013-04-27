<?php 
use \Core\Exceptions;

class Test{
    static function testt()
    {
	try
	{
	    throw new Exceptions\File('Error msg!');
	}
	catch( Exceptions\File $e )
	{
	    die($e);
	}
    }
}
?>
