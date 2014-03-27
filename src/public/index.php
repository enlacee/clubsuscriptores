<?php


// Define path to application directory
defined('APPLICATION_PATH')
    || define(
        'APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application')
    );
//ECHO realpath(dirname(__FILE__) . '/../application');
//require 'Zend/Loader/Autoloader.php';
//ECHO "exit"; EXIT;
//Zend_Loader_Autoloader::getInstance();
//$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/private.ini');

// Define application environment
defined('APPLICATION_ENV')
    || define(
        'APPLICATION_ENV', 
        (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development')
    );

// Ensure library/ is on include_path
$paths = array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $paths));

/** Zend_Application */
// Create application, bootstrap, and run
require 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();