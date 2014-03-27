<?php
$env = 'development';
include('app.php');

//Ejecucion de CronJobs
function run($obj)
{
    echo $obj->descripcion."....Ejecutandose.".PHP_EOL;
    $obj->run();
    echo "Cron ".$obj->descripcion."      [OK]".PHP_EOL;
    echo "TimeDelay: ". $obj->getTimeDelay()." ms".PHP_EOL;
    echo "MemoryUsage: ".$obj->getMemoryUsageKb()." Kbs".PHP_EOL;
}

switch ($argv[2]) {
    case "vigencias":
        $obj = new Cronjob_Cron_DespublicarBeneficios();
        run($obj);
        $obj = new Cronjob_Cron_PublicarBeneficios();
        run($obj);
        break;
    case "despublicarBeneficios":
        $obj = new Cronjob_Cron_DespublicarBeneficios();
        run($obj);
        break;
    case "publicarBeneficios":
        $obj = new Cronjob_Cron_PublicarBeneficios();
        run($obj);
        break;
    case "actualizarDatosSuscriptor":
        $obj = new Cronjob_Cron_VerificarEstadoSuscriptor();
        run($obj);
        break;
    case "actualizarParametros":
        //$obj = new Cronjob_Cron_ActualizarParametros();
        //run($obj);
        break;
    case "generarCatalogo":
        $obj = new Cronjob_Cron_GenerarCatalogo();
        run($obj);
        break;
    case "updateIndexLucene":
        $zlr = new ZendLucene_ReadCron();
        $zlr->read();
        break;
    case "optimizeIndexLucene":
        $zl = new ZendLucene();
        $zl->optimizarIndex();
        break;
    case "-h":
        echo "--------------------------------------------------------".PHP_EOL;
        echo "CRONJOBS para proyecto CLUB DEL SUSCRIPTOR".PHP_EOL;
        echo "--------------------------------------------------------".PHP_EOL;
        echo "Sintax: cron.php [environment] [cronjob]".PHP_EOL;
        echo "environment: development, release, etc".PHP_EOL;
        echo "cronjob: ".PHP_EOL;
        echo "           vigencias".PHP_EOL;
        echo "           despublicarBeneficios".PHP_EOL;
        echo "           publicarBeneficios".PHP_EOL;
        echo "           actualizarDatosSuscriptor".PHP_EOL;
        echo "           actualizarParametros".PHP_EOL;
        echo "           generarCatalogo".PHP_EOL;
        echo "           updateIndexLucene".PHP_EOL;
        echo "           optimizeIndexLucene".PHP_EOL;
        break;
    default:
        echo "No se encuentra la opcion: '".$argv[2]."', vea la ayuda con '-h'";
        break;
    
}
