<?php

$projectPath = realpath(dirname(__FILE__) . '/../src');
$configPath = $projectPath . "/application/configs/application.ini";
$libraryPath = $projectPath . "/library/";
$logPath = $projectPath . "/logs/update_estado_suscriptores.txt";

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


try {
    print 'Actualizacion de Datos de Suscriptor/Beneficiarios';
    echo "\r\n";
    $objSus = new Application_Model_Suscriptor();
    $hashHelper = new App_Controller_Action_Helper_Suscriptor();
    $db = $objSus->getAdapter();
//    $sql = $db->select()->from('suscriptor', array('cant' => new Zend_Db_Expr(('COUNT(*)'))));

    echo 'Recopilando registros...' . PHP_EOL;

    $sql = $db->select()
        ->from(array('s' => 'suscriptor'))
        ->join(array('t' => 't_actsuscriptor'), 't.des_numdocid = s.numero_documento AND t.cod_tipdocid = s.tipo_documento', array())
        ->where('t.id = (SELECT MAX(tt.id) FROM t_actsuscriptor AS tt WHERE tt.des_numdocid = s.numero_documento AND tt.cod_tipdocid = s.tipo_documento)')
        ->where("((t.est_sus = 'AC' AND s.es_suscriptor = 0) OR (t.est_sus != 'AC' AND s.es_suscriptor = 1)) OR
                (t.estado_beneficiario != s.es_invitado) OR
                ((t.estado_reparto = 'AC' AND s.activo = 0) OR (t.estado_reparto != 'AC' AND s.activo = 1))");
    $suscriptores = $db->fetchAll($sql);
    $total = count($suscriptores);

    $p = new App_Util_Console_ProgressBar('Registros Procesados %fraction% [%bar%] %percent% ETA: %estimate% ET: %elapsed%', '=>', '-', 120, $total);
    $i = 1;
    foreach ($suscriptores as $suscriptor) {
        try {
            $db->beginTransaction();
            $nuevosDatos = array();
            $migrado = Application_Model_ActSuscriptor
                ::getLastRecordSuscriptorPorDocumento($suscriptor['tipo_documento'], $suscriptor['numero_documento']);


            if (!empty($migrado)) {
                $nuevosDatos['nombres'] = $migrado['des_nombres'];
                $nuevosDatos['apellidos'] = $migrado['des_apepaterno'] . ' ' . $migrado['des_apematerno'];
                $nuevosDatos['apellido_paterno'] = $migrado['des_apepaterno'];
                $nuevosDatos['apellido_materno'] = $migrado['des_apematerno'];
                $nuevosDatos['sexo'] = $migrado['tip_sexoente'] == 'F' ? 'F' : 'M';
                $nuevosDatos['codigo_suscriptor'] = $migrado['cod_entesuscriptor'];
                $nuevosDatos['telefono'] = $migrado['des_numtelf01'];
                $nuevosDatos['activo'] = $migrado['estado_reparto'] == 'AC' ? 1 : 0;
                $nuevosDatos['es_invitado'] = $migrado['estado_beneficiario'] == 1 ? 1 : 0;
                $nuevosDatos['fecha_invitacion'] = $migrado['fch_registro'];
                $nuevosDatos['fecha_actualizacion'] = date('Y-m-d H:i:s');
                $nuevosDatos['es_suscriptor'] = $migrado['est_sus'] == 'AC' ? 1 : 0;
                $nuevosDatos['email_contacto'] = $migrado['des_email'];
                if ($nuevosDatos['es_invitado'] != 1) {
                    $nuevosDatos['suscriptor_padre_id'] = null;
                }


                $nuevosDatos["fecha_nacimiento"] = null;
                if (!empty($migrado['fch_nacimiento']) && $migrado['fch_nacimiento'] != 'n') {
                    $f = explode("/", $migrado["fch_nacimiento"]);
                    if (count($f) == 3) {
                        $nuevosDatos["fecha_nacimiento"] = $f[2] . "-" . $f[1] . "-" . $f[0];
                    } else {
                        $nuevosDatos["fecha_nacimiento"] = $migrado["fch_nacimiento"];
                    }
                }
            }
            if (empty($suscriptor['hash'])) {
                $nuevosDatos['hash'] = $hashHelper->generaHashSuscriptor($suscriptor['id']);
            }
            $where = $db->quoteInto('id = ?', $suscriptor['id']);
            $objSus->update($nuevosDatos, $where);
            $db->commit();
            $p->update($i++);
        } catch (Exception $e) {
            $db->rollBack();
            $log->log('s_id:' . $suscriptor['id'] . '['. $e->getMessage() .']', Zend_Log::ERR);
        }
    }

    echo "Done!" . PHP_EOL;
} catch (Exception $exc) {
    echo $exc->getMessage() . '\n';
    echo $exc->getTraceAsString() . '\n';
}
    