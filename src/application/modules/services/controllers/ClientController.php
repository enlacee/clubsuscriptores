<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of SoapController
 *
 * @author Computer
 */
class Services_ClientController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }

    public function suscriptorAction()
    {
        try {
            //$client = new Zend_Soap_Client('http://devel.clubsc.info/services/soap/suscriptor');
            $client = new Zend_Soap_Client('http://dev.clubsuscriptores.pe/services/soap/suscriptor');
            $result = $client->verificarSuscriptor('DNI41253678', '5112029400');
            var_dump($result);
        } catch (SoapFault $s) {
            die(
                'ERROR: [' . $s->faultcode . '] '
                . $s->faultstring . '<br>[' . $s->getTraceAsString() . ']'
            );
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function establecimientoAction()
    {
        try {
            $client = new Zend_Soap_Client('http://devel.clubsc.info/services/soap/establecimiento');
            $result = $client->redimirConsumo(
                'DNI23232324', '2653112', 'B20111200009', 'BV-122544545', 12.56
            );
            var_dump($result);
        } catch (SoapFault $s) {
            die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring . '<br>[' . $s->getTraceAsString() . ']');
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

//    public function tAction()
//    {
//        $o = new Application_Model_SuscriptorValidar();
//        $o->datosSuscriptor('dni', '43434343',true);
//        echo "<br><br><br>";
//        //$o->datosSuscriptor('dni', '43434343', true);
//        exit;
//    }

    public function sqlClientAction()
    {
        
        /**
resources.multidb.db2.dbname = suscriptores_actualizacion
resources.multidb.db2.adapter = Pdo_Mssql
resources.multidb.db2.host = '174.129.182.127,30789'
resources.multidb.db2.username = des_clasificados
resources.multidb.db2.password = ye5ioFe1taiWoL
resources.multidb.db2.charset = UTF-8
resources.multidb.db2.pdoType = dblib
 
         */
        
        $db3 = Zend_Db::factory('pdo_mssql', array(
            'dbname' => 'suscriptores_actualizacion',
            'host' => '174.129.182.127',
            'port' => '30789',
            'username' => 'des_clasificados',
            'password' => 'ye5ioFe1taiWoL',
            'charset' => 'UTF-8',
            'pdoType' => 'dblib'
        ));
        var_dump($db3->fetchAll("select * from t_actsuscriptor where cod_tipdocid = 'DNI'
            and des_numdocid ='19825715' ;"));
    }

    public function suscriptorvalidarAction()
    {
        try {
            $client = new Zend_Soap_Client(
                'http://dev.clubsuscriptores.pe/services/soap/suscriptorvalidar', 
                array('proxy_host' => '172.19.0.4', 'proxy_port' => '9090')
            );
            $result = $client->ValidarSuscriptor('DNI', '43308541');
            var_dump($result);
        } catch (SoapFault $s) {
            die(
                'ERROR: [' . $s->faultcode . '] '
                . $s->faultstring . '<br>[' . $s->getTraceAsString() . ']'
            );
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

}
