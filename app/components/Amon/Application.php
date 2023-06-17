<?php

namespace Amon;

use Closure;
use Amon\Application\AbstractApplication;
use Amon\Di\DiInterface;
use Amon\Application\Exception;
use Amon\Helper\Other;
use Amon\Helper\Str;

class Application extends AbstractApplication
{
	private string $environment;
	protected $config;
	protected $dirs;
    protected $_routers;
    protected $router;

    public function __construct()
    {
		$this->config = $this->di->getConfig();
		$this->dirs = $this->di->getLoader()->getDirs(true);
        $this->environment = $this->config->application->environment;
        $this->_routers = include APP_PATH . '/config/routers.php';
        $this->router = $this->di->getRouter($this->di->getRouters(), $this->dirs->controllersDir);

		$this->boot();

        \error_reporting(0);
        if ($this->environment === 'dev') {
            \error_reporting(E_ALL);
            \ini_set('display_errors', '1');
        }
    }

	public function getContent()
	{
		$this->router->route();
	}

    private function boot(): void
    {
        date_default_timezone_set($this->config->application->timezone);
    }
}	
