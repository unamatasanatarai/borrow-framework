<?php
namespace Borrow;

class Controller{
    public $action = 'index';
    public $params = array();

	public function __construct($method, $params = array())
	{
		$this->action = $method;
		$this->params = $params;

		$this->beforeFilter();
		$content = $this->$method();

		if ( extension_loaded( 'zlib' ) ) { ob_start( 'ob_gzhandler' ); }
		echo $content;
        if ( extension_loaded( 'zlib' ) ) { ob_end_flush(); }
	}

    public function beforeFilter(){}

}
