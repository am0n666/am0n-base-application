<?php

use Amon\Helper\Str;

class ErrorController extends ControllerBase
{
    public function notfoundAction()
    {
		echo $this->render(['notfound' => 'Page not found']);
    }
}

