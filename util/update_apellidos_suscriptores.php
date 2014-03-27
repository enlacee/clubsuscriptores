<?php

$projectPath = realpath(dirname(__FILE__) . '/../src');
$configPath = $projectPath . "/application/configs/application.ini";
$libraryPath = $projectPath . "/library/";
$logPath = $projectPath . "/logs/update_apellidos_suscriptores.txt";

require 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('App_');
$loader->registerNamespace('Application_');
defined('APPLICATION_PATH') || define('APPLICATION_PATH', $projectPath . '/application');
$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/private.ini');
$env = count($argv) > 1 ? $argv[1] : $config->env;

define("APPLICATION_ENV", $env);
date_default_timezone_set('America/Lima');

$paths = array($libraryPath, get_include_path());
set_include_path(implode(PATH_SEPARATOR, $paths));


$config = new Zend_Config_Ini($configPath, $env);
$log = new Zend_Log(new Zend_Log_Writer_Stream($logPath));

// Create application, bootstrap, and run
defined('APPLICATION_ENV') || define('APPLICATION_ENV', $env);
$application = new Zend_Application($env, $configPath);
$application->bootstrap();
$db = $application->getBootstrap()->getPluginResource('multidb')->getDb('db');


try {
    print 'Actualizacion de Datos de Suscriptor/Beneficiarios (apellidos)';
    echo "\r\n";
    $objSus = new Application_Model_Suscriptor();
    $objTact = new Application_Model_ActSuscriptor();
    $db = $objSus->getAdapter();
    $dbAct = $objTact->getAdapter();
    $sql = $db->select()->from(array('s' => 'suscriptor'))->where('apellido_paterno IS NULL AND apellidos IS NOT NULL');
    $suscriptores = $db->fetchAll($sql);
    $total = count($suscriptores) > 0 ? count($suscriptores) : 1;
    $p = new App_Util_Console_ProgressBar('Registros Procesados %fraction% [%bar%] %percent% ETA: %estimate%', '=>', '-', 120, $total);
    $i = 1;
    foreach ($suscriptores as $suscriptor) {
        $sql1 = $dbAct->select()
                ->from(array('t' => 't_actsuscriptor'))
                ->where('t.cod_tipdocid = ?', $suscriptor['tipo_documento'])
                ->where('t.des_numdocid = ?', $suscriptor['numero_documento'])
                ->order('t.id DESC')->limit(1);
        $sus = $dbAct->fetchRow($sql1);
        if (empty($sus)) {
            $where = $db->quoteInto('id = ?', $suscriptor['id']);
            $objSus->update(array('apellido_paterno' => $suscriptor['apellidos']), $where);
        } else {
            $where = $db->quoteInto('id = ?', $suscriptor['id']);
            $objSus->update(
                array(
                'apellido_paterno' => $sus['des_apepaterno'],
                'apellido_materno' => $sus['des_apematerno']
                ), $where);
        }
        $p->update($i++);
    }
    echo "\r\n";
} catch (Exception $exc) {
    echo $exc->getMessage() . '\n';
    echo $exc->getTraceAsString() . '\n';
}
    