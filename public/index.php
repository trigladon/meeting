<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));
defined('DIR_ROOT') or define('DIR_ROOT', dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

//try{
    Zend\Mvc\Application::init(require 'config/application.config.php')->run();
//}catch (\Exception $e)
//{
//    $errorLogger = new \Common\Service\ErrorLogger();
//    $errorLogger->logException($e);
//
//    header('Location: ' . '/work.html', 302);
//    exit();
//}
