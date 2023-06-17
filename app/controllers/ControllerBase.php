<?php

use Amon\Mvc\Controller;
use Amon\Helper\Str;

class ControllerBase extends Controller
{
	protected $dirs;

	public function __construct () {
	}

    public function render($data = []): string
    {
		$this->dirs = $this->di->getLoader()->getDirs(true);

		$controllername = str_replace('controller', '', Str::lower(debug_backtrace()[1]['class']));
		$actionname = str_replace('action', '', Str::lower(debug_backtrace()[1]['function']));

		if (!is_file($this->dirs->viewsDir . $controllername . DIRECTORY_SEPARATOR . $actionname . '.html'))
			throw new \Exception('No view file for this method: ' . $this->dirs->viewsDir . $controllername . DIRECTORY_SEPARATOR . $actionname . '.html');

		return $this->view->render($controllername . DIRECTORY_SEPARATOR . $actionname . '.html', $data);
    }
}

