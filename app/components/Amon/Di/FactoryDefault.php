<?php

namespace Amon\Di;

class FactoryDefault extends \Amon\Di\Di
{
    public function __construct()
    {
        parent::__construct();
		$this->services=[
			"router"				=>		(new Service("Amon\\Routing\\Router", TRUE )),
			"url"					=>		(new Service("Amon\\Routing\\Url", TRUE ))
		];
    }
}
