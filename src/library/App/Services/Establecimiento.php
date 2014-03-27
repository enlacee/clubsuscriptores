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
class App_Services_Establecimiento
{
    
    protected $_wslog;
    protected $_helper;

    public function __construct()
    {
        $this->_wslog = new Zend_Log(
            new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/ws_establecimiento.log')
        );
        $this->_helper = new App_Controller_Action_Helper_ContadoresCupon();
    }

    /**
     *
     * @param string $documento
     * @param string $telefono
     * @param string $codigo
     * @param string $voucher
     * @param float $monto
     * @return string
     */
    public function redimirConsumo($documento, $telefono, $codigo = null, $voucher = null,
                                   $monto = null)
    {
        try {
            $establecimiento = Application_Model_Establecimiento::
                getEstablecimientoByNumeroTelefonico($telefono);
            if (isset($establecimiento['id'])) {
                if ($establecimiento['activo'] == 1) {
                    $tipo = substr($documento, 0, 3);
                    $numero = substr($documento, 3);

                    switch (strtoupper($tipo)) {
                        case 'DNI':
                            $subject = Application_Model_Suscriptor::
                                getSuscriptorActivoByDocumento($tipo, $numero);
                            if (!isset($subject['id']))
                                throw new App_Services_Exception('Suscriptor no valido', 203);
                            break;
                        case 'CEX':
                            $subject = Application_Model_Suscriptor::
                                getSuscriptorActivoByDocumento($tipo, $numero);
                            if (!isset($subject['id']))
                                throw new App_Services_Exception('Suscriptor no valido', 203);
                            break;
                        case 'RUC':
                            $subject = Application_Model_Suscriptor::
                                getSuscriptorActivoByDocumento($tipo, $numero);
                            if (!isset($subject['id']))
                                throw new App_Services_Exception('Suscriptor no valido', 203);
                            break;
                        case 'PAS':
                            $subject = Application_Model_Suscriptor::
                                getSuscriptorActivoByDocumento($tipo, $numero);
                            if (!isset($subject['id']))
                                throw new App_Services_Exception('Suscriptor no valido', 203);
                            break;
                        case 'CUP':
                            $subject = Application_Model_Cupon::getCuponByCodigo($numero);
                            if (!isset($subject['id']))
                                throw new App_Services_Exception('Numero de cupon no valido', 209);
                            if (
                                $subject['estado'] == Application_Model_Cupon::ESTADO_CONSUMIDO ||
                                $subject['estado'] == Application_Model_Cupon::ESTADO_REDIMIDO ||
                                $subject['estado'] == Application_Model_Cupon::ESTADO_CONCILIADO
                            )
                                throw new App_Services_Exception('Cupon ya consumido', 210);
                            break;
                        default :
                            throw new App_Services_Exception(
                                "El documento ingresado es invalido", 208
                            );
                            break;
                    }
                    if (!is_null($codigo)) {
                        $beneficio = Application_Model_Beneficio::getBeneficiosByCodigo($codigo);
                        if ($beneficio['est_id'] == $establecimiento['id']) {
                            if (strtoupper($tipo) == 'CUP') {
                                if ($subject['beneficio_id'] != $beneficio['id']) {
                                    throw new App_Services_Exception(
                                        'El cupon no corresponde al beneficio', 211
                                    );
                                }
                                $suscriptor = Application_Model_Suscriptor::getSuscriptorById(
                                        array("suscriptor_id"=>$id));
                                return $this->redimirByCupon(
                                    $beneficio, 
                                    $suscriptor, 
                                    $subject, 
                                    $voucher, 
                                    $monto
                                );
                            } else {
                                return $this->redimirBySuscriptor(
                                    $subject, $beneficio, $voucher, $monto
                                );
                            }
                        } else {
                            throw new App_Services_Exception(
                                'La promo no corresponde al establecimiento', 201
                            );
                        }
                    } else {
                        throw new App_Services_Exception('Codigo de beneficio no valido', 207);
                    }
                } else {
                    throw new App_Services_Exception('Establecimiento no valido', 204);
                }
            } else {
                throw new App_Services_Exception('Numero telefonico no valido', 205);
            }
        } catch (Exception $exc) {
            $this->_wslog->log("[" . $exc->getCode() . "]" . $exc->getMessage(), Zend_Log::ERR);
            return array("status" => $exc->getCode(), 'message' => $exc->getMessage());
        }
    }

    private function redimirBySuscriptor($suscriptor, $beneficio, $voucher, $monto)
    {
        try {
            $cupones = Application_Model_Cupon
                ::getCuponesGeneradosBySuscriptorAndBeneficio($suscriptor['id'], $beneficio['id']);
            if (count($cupones) > 0) {
                $cupon = each($cupones);
                return $this->redimirByCupon($beneficio, $suscriptor, $cupon[1], $voucher, $monto);
            } else {
                $obj = new Application_Model_Cupon();
                $obj->setSuscriptor_id($suscriptor['id']);
                $obj->setBeneficio_id($beneficio['id']);
                $nroCupones = $obj->getNroCuponesGenBySuscrip();
                if (
                    $beneficio['sin_limite_por_suscriptor'] == 1 ||
                    ($beneficio['sin_limite_por_suscriptor'] == 0 &&
                    $beneficio['maximo_por_subscriptor'] > $nroCupones)
                ) {
                    if ($beneficio['stock_actual'] > 0 || $beneficio['sin_stock'] == 1) {
                        $db = $obj->getAdapter();
                        $db->beginTransaction();
                        $date = date('Y-m-d H:i:s');
                        $cupon = array(
                            'suscriptor_id' => $suscriptor['id'],
                            'beneficio_id' => $beneficio['id'],
                            'codigo' => date('Ymdhis') . '1' . rand(1, 9),
                            'fecha_emision' => $date,
                            'estado' => Application_Model_Cupon::ESTADO_GENERADO
                        );
                        $lastId = $obj->insert($cupon);

                        if ($beneficio['sin_stock'] != 1) {
                            $objBeneficioVersion = new Application_Model_BeneficioVersion();
                            $objBeneficioVersion->setBeneficio_id($beneficio['id']);
                            $objBeneficioVersion->setDisminuyeStockActual(1);
                        }

                        $modelSusBen = new Application_Model_SuscriptorBeneficio();
                        $arrayCantCupon = $modelSusBen->getCuponesXGenerar(
                            $suscriptor['id'], $beneficio['id']
                        );

                        if (empty($arrayCantCupon['id'])) {
                            $this->_helper->insertSuscriptorBeneficio(
                                $suscriptor['id'], $beneficio['id']
                            );
                        }
                        $this->_helper->actualizarCuponGeneradoSuscriptorBeneficio(
                            $suscriptor['id'], $beneficio['id']
                        );

                        $db->commit();
                        $cupon['id'] = $lastId;
                        return $this->redimirByCupon(
                            $beneficio, $suscriptor, $cupon, $voucher, $monto
                        );
                    } else {
                        throw new App_Services_Exception(
                            'No hay Stock', 202
                        );
                    }
                } else {
                    throw new App_Services_Exception(
                        'Consumo del suscriptor sobrepasa el limite', 206
                    );
                }
            }
        } catch (App_Services_Exception $e) {
            throw new App_Services_Exception($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            $db->rollBack();
            return $e->getMessage();
        }
    }

    private function redimirByCupon($beneficio, $suscriptor, $cupon, $voucher, $monto)
    {
        try {
            if (!isset($cupon['id'])) {
                return array('status' => 'NOK', 'message' => 'Error en el Cupon');
            }
            $obj = new Application_Model_Cupon();
            $db = $obj->getAdapter();
            $db->beginTransaction();
            $where = $db->quoteInto('id = ?', $cupon['id']);
            $date = date('Y-m-d H:i:s');
            $obj->update(
                array(
                'estado' => Application_Model_Cupon::ESTADO_REDIMIDO,
                'medio_redencion' => Application_Model_Cupon::MEDIO_REDENCION_SMS,
                'numero_comprobante' => $voucher,
                'monto_descontado' => $monto,
                'fecha_consumo' => $date,
                'fecha_redencion' => $date
                ), $where
            );
            $this->_helper->actualizarCuponConsumidoSuscriptorBeneficio(
                $suscriptor['id'], $beneficio['id']
            );
            $db->commit();
            return array('status' => 'OK', 'message' => 'Consumo redimido');
        } catch (Exception $e) {
            $db->rollBack();
            return array('status' => 'NOK', 'message' => 'No se completo la redencion');
        }
    }

}