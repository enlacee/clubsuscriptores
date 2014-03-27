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

defined('APPLICATION_ENV') || define('APPLICATION_ENV', $env);
$application = new Zend_Application($env, $configPath);
$application->bootstrap();


try {
    print 'Actualizacion de Stock para versiones de Beneficios';
    echo "\r\n";
    $objBeneVer = new Application_Model_BeneficioVersion();
    $objCupon = new Application_Model_Cupon();
    $db = $objBeneVer->getAdapter();
    $dbCupon = $objCupon->getAdapter();
    
    $sql = $db->select()
        ->from(array('bv' => 'beneficio_version'))
        ->join(array('b' => 'beneficio'),'bv.beneficio_id = b.id', array())
        ->where('(SELECT COUNT(*) FROM beneficio_version as bv2 WHERE bv2.beneficio_id = b.id) > 1')
        ->where('b.sin_stock <> 1');
    
    $versiones = $db->fetchAll($sql);
    $total = count($versiones) > 0 ? count($versiones) : 1;
    
    
    $p = new App_Util_Console_ProgressBar('Registros Procesados %fraction% [%bar%] %percent% ETA: %estimate%', '=>', '-', 120, $total);
    $i = 1;
    foreach ($versiones as $version) {
        
        $count = $dbCupon->select()
            ->from(array('c' => 'cupon'))
            ->where('c.beneficio_id = ?', $version['beneficio_id'])
            ->where('c.fecha_emision >= ?', $version['fecha_registro']);
        
        if(empty($version['activo'])) {
            $sql = $db->select()
                ->from(array('bv' => 'beneficio_version'))
                ->where('bv.beneficio_id = ?', $version['beneficio_id'])
                ->where('bv.fecha_registro > ?', $version['fecha_registro'])
                ->order('bv.fecha_registro ASC')->limit(1);
            $fin = $db->fetchRow($sql);
            $count = $count->where('c.fecha_emision < ?', $fin['fecha_registro']);
        }
        $cupones = $dbCupon->fetchAll($count);
        $consumidos = count($cupones);
        $stock_actual = $version['stock'] - $consumidos;
        $where = $db->quoteInto('id = ?', $version['id']);
        $objBeneVer->update(array('stock_actual' => $stock_actual), $where);
        $p->update($i++);
    }
    echo "\r\n";
} catch (Exception $exc) {
    echo $exc->getMessage() . '\n';
    echo $exc->getTraceAsString() . '\n';
}
    