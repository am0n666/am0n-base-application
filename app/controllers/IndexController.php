<?php

use Amon\Helper\Str;

class IndexController extends ControllerBase
{
    public function IndexAction()
    {
		$words = ['this', 'is', 'some', 'words'];
		echo $this->render(['words' => $words]);
    }

    public function AboutAction()
    {
		echo $this->render(['about' => 'About Us!']);
    }
}

