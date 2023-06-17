<?php

namespace Amon\Routing;

use \Amon\Routing\Url;

class Router
{
	protected $_url;
	protected $routes = array();
	protected $basePath = '';
	protected $controllersDir = '';

	private $standardController	=	"index";
	private $standardAction 	= 	"index";
	
	private $standardErrorController	=	"error";
	private $standardErrorAction		=	"notfound";

	public function __construct($routes = array(), $controllersDir) {
		$this->_url = new Url();
		$this->addRoutes($routes);
		$this->setBasePath($this->_url->getBasePath());
		$this->setControllersDir($controllersDir);
	}

	public function add($name, $pattern, $method = 'GET') {
		$multi_methods = false !== strpos($method, '|');
		if ($multi_methods) {
			$method = explode('|', $method);
		}else{
			$method = [$method];
		}

		if($this->isDeclared($name)) {
			throw new \Exception("Can not redeclare route '{$name}'");
		}

		$this->routes[] = [
			'name' => $name,
			'pattern' => $pattern,
			'method' => $method,
		];
	}
	
    public function getRoutes()
    {
        return $this->routes;
    }

    public function matches()
    {
		$pathinfo = str_replace($this->basePath, '', $this->_url->getRequestUri());
		$_result = [];
		$correct_method = false;
		$pathinfo = rawurldecode(rtrim(explode('?', $pathinfo)[0], '/')) ?: '/';
		$parsed_url = parse_url($_SERVER['REQUEST_URI']);//Parse Uri

		if(isset($parsed_url['path'])){
			$path = str_replace($this->basePath, '', $parsed_url['path']);
		}else{
			$path = '/';
		}
		$method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->routes as $route) {
			if(preg_match("@^" . $route['pattern'] . "$@D", $path)) {
				$correct_method = true === in_array($method, $route['method']);
				if ($correct_method) {
					$_result['route_name'] = $route['name'];
					$_result['url'] = $route['pattern'];
				}
			}
		}
		return $_result;
    }

	public function isDeclared(string $router_name)
	{
		$i = 0;
		foreach ($this->routes as $route) {
			if ($route['name'] === $router_name)
				return(true);
			$i++;
		}
		return(false);
	}

	public function addRoutes($routes){
		if(!is_array($routes) && !$routes instanceof Traversable) {
			throw new \Exception('Routes should be an array or an instance of Traversable');
		}
		foreach($routes as $router_name => $route) {
			$this->add($router_name, $route['url'], $route['method']);
		}
	}

	public function setBasePath($basePath) {
		$this->basePath = $basePath;
	}

	public function getBasePath() {
		return $this->basePath;
	}

	public function setControllersDir($controllersDir) {
		$this->controllersDir = $controllersDir;
	}

	public function getControllersDir() {
		return $this->controllersDir;
	}

	public function route() {
		$matches = $this->matches();
		if (!empty($matches)) {
			$url = $matches['url'];
			$url = trim($url,'/');
			if(empty($url)) $url = $this->standardController . '/' . $this->standardAction;
			$arrPath = explode('/', $url);
	
			$contollerName = ucfirst(strtolower(array_shift($arrPath))) . 'Controller';
			$actionName = ucfirst(strtolower(array_shift($arrPath))) . 'Action';
			$contollerFile = $this->getControllersDir() . $contollerName . '.php';
			if(!file_exists($contollerFile)||!class_exists($contollerName)){
				$this->error404();
				return;
			}
	
			$Controller = new $contollerName();
	
			if(!method_exists($Controller, $actionName)){
				$this->error404($uri);
				return;
			}
			
			if(!count($arrPath)){
				call_user_func([$Controller, $actionName]);
			}else{
				call_user_func_array([$Controller, $actionName], $arrPath);
			}
		}else{
			$this->error404();
			return;
		}
	}

    protected function error404(){
		$url = $this->standardErrorController . '/' . $this->standardErrorAction;

		$arrPath = explode('/', $url);
		$contollerName = ucfirst(strtolower(array_shift($arrPath))) . 'Controller';
		$actionName = ucfirst(strtolower(array_shift($arrPath))) . 'Action';

        $Controller = new $contollerName();
		if(!count($arrPath)){
			call_user_func([$Controller, $actionName]);
		}else{
			call_user_func_array([$Controller, $actionName], $arrPath);
		}
    }
}
