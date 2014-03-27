<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Suscriptor
 *
 * @author FCJ
 */
class App_Services_Suscriptor
{
    /**
     *
     * @var Zend_Log
     */
    protected $_wslog;

    public function __construct()
    {
        $this->_wslog = new Zend_Log(
            new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/ws_suscriptor.log')
        );
    }

    /**
     *
     * @param string $documento
     * @param string $telefono
     * @return string
     */
    public function verificarSuscriptor($documento, $telefono = null)
    {
        try {
            if (!is_null($telefono))
                $establecimiento = Application_Model_Establecimiento::getEstablecimientoByNumeroTelefonico($telefono);
            else
                $establecimiento = null;
            if ((empty($establecimiento['id']) && !empty($telefono)) 
                || (empty($establecimiento['nt_activo']) && !empty($telefono))) {
                throw new App_Services_Exception('Número telefónico no valido o inhabilitado', 205);
            } else {
                if (empty($establecimiento['activo']) && !empty($telefono)) {
                    throw new App_Services_Exception('Establecimiento no valido', 204);
                } else {
                    $tipo = substr($documento, 0, 3);
                    $numero = substr($documento, 3);
                    $suscriptor = Application_Model_Suscriptor::getSuscriptorActivoByDocumento($tipo, $numero);
                    if (!empty($suscriptor['id'])) {
                        if (!empty($telefono)) {
                            $beneficios = Application_Model_Beneficio
                                ::getBeneficiosActivosByEstaIdAndSusId($establecimiento['id'], $suscriptor['id']);
                            $codigos = array();
                            foreach ($beneficios as $beneficio) {
                                $codigos[] = $beneficio['codigo'];
                            }
                            return array('status' => 'SI', 'promociones' => $codigos);
                        } else {
                            return array('status' => 'SI');
                        }
                    } else {
                        return array('status' => 'NO');
                    }
                }
            }
        } catch (Exception $exc) {
            $this->_wslog->log("[" . $exc->getCode() . "]" . $exc->getMessage(), Zend_Log::ERR);
            return array("status" => $exc->getCode(), 'message' => $exc->getMessage());
        }
    }

}