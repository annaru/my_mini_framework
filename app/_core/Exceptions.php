<?php namespace Core\Exceptions;

abstract class MyException extends \Exception {
    public function fullinfo(){
	return $this->getMessage().' in '.$this->getFile();
    }
}

class File extends namespace\MyException {
    
}
class Load extends namespace\MyException {
    
}

?>
