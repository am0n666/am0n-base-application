<?php

namespace Amon\Mvc;

use Amon\Di\Injectable;
use Amon\View\ViewInterface;
use Amon\Helper\Str;

abstract class Controller extends Injectable implements ControllerInterface {
	 public  function __construct (  )  {
		if (method_exists($this,"onConstruct")) {
			$this->onConstruct();
		}
	}

	public function getControllerName() {
		return str_replace('controller', '', Str::lower(debug_backtrace()[1]['class']));
	}

	public function getActionName() {
		return str_replace('action', '', Str::lower(debug_backtrace()[1]['function']));
	}
}
?>
