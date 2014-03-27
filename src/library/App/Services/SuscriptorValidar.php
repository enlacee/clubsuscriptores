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
class App_Services_SuscriptorValidar
{
    /**
     *
     * @var Zend_Log
     */
    protected $_wslog;

    public function __construct()
    {
        $this->_wslog = new Zend_Log(new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/ws_suscriptor.log'));
    }

    /**
     * Para QuiscoDigital
     * @param string $tipodoc
     * @param string $nrodoc
     * @param string $codprod
     * @return array
     */
    public function ValidarSuscriptor1($tipodoc, $nrodoc, $codprod = null, $distrito = null)
    {
        try {
            $datos = Application_Model_SuscriptorValidar::datosSuscriptor($tipodoc, $nrodoc);
            if (count($datos) == 0 || !is_array($datos)) {
                if ($tipodoc == 'DNI' && strlen($nrodoc) == 8) {
                    $tipodocuno = 'RUC';
                    $nrodocuno = Application_Model_SuscriptorValidar::generarRUC($nrodoc);
                    $datos = Application_Model_SuscriptorValidar::datosSuscriptor($tipodocuno, $nrodocuno);
                }
                if ($tipodoc == 'RUC' && substr($nrodoc, 0, 2) == '10' && strlen($nrodoc) == 11) {
                    $tipodocuno = 'DNI';
                    $nrodocuno = substr($nrodoc, 2, 8);
                    $datos = Application_Model_SuscriptorValidar::datosSuscriptor($tipodocuno, $nrodocuno);
                }
            }
            if (!is_null($datos['Cod_Suscriptor'])) {
                $productos = Application_Model_SuscriptorValidar
                    ::productosSuscriptor($datos['Cod_Suscriptor'], $codprod);
                $direcciones = Application_Model_SuscriptorValidar
                    ::distritosSuscriptor($datos['Cod_Suscriptor'], $distrito);
            }
            if (count($datos) > 0 && count($productos) > 0 && count($direcciones) > 0) {
                return array(
                    'Respuesta' => 'OK',
                    'Mensaje' => 'Sin Errores',
                    'cabecera' => $datos,
                    'detalle' => $productos,
                    'distritos' => $direcciones
                );
            } else {
                if (count($datos) == 0 || !is_array($datos))
                    $msg = 'No se encontraron datos del suscriptor';
                elseif (count($productos) == 0)
                    $msg = 'No se encontraron productos del suscriptor';
                elseif (count($direcciones) == 0)
                    $msg = 'No se encontraron distritos del suscriptor';
                return array('Respuesta' => 'Error', 'Mensaje' => $msg, 'cabecera' => array('EsSuscriptor' => 0));
            }
        } catch (Exception $exc) {
            $this->_wslog->log("[" . $exc->getCode() . "]" . $exc->getMessage(), Zend_Log::ERR);
            return array("status" => $exc->getCode(), 'message' => $exc->getMessage());
        }
    }

    /**
     * Para Ofertop
     * @param string $tipodoc
     * @param string $nrodoc
     * @param string $codprod
     * @return array
     */
    public function ValidarSuscriptor($tipodoc, $nrodoc, $codprod = null, $distrito = null)
    {
        try {
            $datos = Application_Model_SuscriptorValidar::datosSuscriptor($tipodoc, $nrodoc, true);
            if (count($datos) == 0 || !is_array($datos)) {
                if ($tipodoc == 'DNI' && strlen($nrodoc) == 8) {
                    $tipodocuno = 'RUC';
                    $nrodocuno = Application_Model_SuscriptorValidar::generarRUC($nrodoc);
                    $datos = Application_Model_SuscriptorValidar::datosSuscriptor($tipodocuno, $nrodocuno);
                }
                if ($tipodoc == 'RUC' && substr($nrodoc, 0, 2) == '10' && strlen($nrodoc) == 11) {
                    $tipodocuno = 'DNI';
                    $nrodocuno = substr($nrodoc, 2, 8);
                    $datos = Application_Model_SuscriptorValidar::datosSuscriptor($tipodocuno, $nrodocuno);
                }
            }
            if (!is_null($datos['Cod_Suscriptor'])) {
                $productos = Application_Model_SuscriptorValidar
                    ::productosSuscriptor($datos['Cod_Suscriptor'], $codprod);
                $direcciones = Application_Model_SuscriptorValidar
                    ::distritosSuscriptor($datos['Cod_Suscriptor'], $distrito);
            }
            
            if (count($datos) > 0 && is_array($datos)) {
                if (!empty($datos['cod_entesuscriptor']) && (count($productos) > 0 && count($direcciones) > 0)) {
                    return array(
                        'Respuesta' => 'OK',
                        'Mensaje' => 'Sin Errores',
                        'cabecera' => $datos,
                        'detalle' => $productos,
                        'distritos' => $direcciones
                    );
                } elseif (empty($datos['cod_entesuscriptor'])) {
                    return array(
                        'Respuesta' => 'OK',
                        'Mensaje' => 'Sin Errores',
                        'cabecera' => $datos,
                        'detalle' => '',
                        'distritos' => ''
                    );
                } else {
                    if (count($productos) == 0) {
                        $msg = 'No se encontraron productos del suscriptor';
                    } elseif (count($direcciones) == 0) {
                        $msg = 'No se encontraron distritos del suscriptor';
                    }
                    return array('Respuesta' => 'Error', 'Mensaje' => $msg, 'cabecera' => array('EsSuscriptor' => 0));
                }
                
            } else {
                if (count($datos) == 0 || !is_array($datos)){
                    $msg = 'No se encontraron datos del suscriptor';
                }
                return array('Respuesta' => 'Error', 'Mensaje' => $msg, 'cabecera' => array('EsSuscriptor' => 0));
            }
        } catch (Exception $exc) {
            $this->_wslog->log("[" . $exc->getCode() . "]" . $exc->getMessage(), Zend_Log::ERR);
            return array("status" => $exc->getCode(), 'message' => $exc->getMessage());
        }
    }

}
