<?php

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', BASE_PATH . 'app' . DIRECTORY_SEPARATOR);

include APP_PATH . '/core/init.php';

use Amon\Di\FactoryDefault;

try {
    $di = new FactoryDefault();

	include APP_PATH . '/config/services.php';

    $application = new \Amon\Application($di);

	$application->getContent();
} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
