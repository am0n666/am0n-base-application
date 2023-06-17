<?php
declare(strict_types=1);

use Amon\Helper\Other;
use Amon\View\Twig;
use Amon\TwigExtensions\publicDir;

$di->setShared('loader', function () use ($loader) {
	return $loader;
});

$di->setShared('routers', function () {
    return include APP_PATH . "/config/routers.php";
});

$di->setShared('config', function () {
    return Other::toObject(include APP_PATH . "/config/config.php");
});

$di['view'] = function () {
	$dirs = $this->getLoader()->getDirs(true);
    $view = new Twig(
		$dirs->viewsDir, [
			'debug' => false,
			'cache' => false,
			'autoescape' => false
		]);
		$view->addExtension(new \Amon\TwigExtensions\publicDir());
    return $view;
};
