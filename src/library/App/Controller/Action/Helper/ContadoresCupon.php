<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class App_Controller_Action_Helper_ContadoresCupon extends Zend_Controller_Action_Helper_Abstract
{
    
    public function actualizarCuponConsumidoSuscriptorBeneficio($idSus, $idBen, $aumento=1)
    {
        $modelSusBen = new Application_Model_SuscriptorBeneficio();
        $arraySusBen = $modelSusBen->actualizarCuponRedimido($idSus, $idBen);
        
        $where = $modelSusBen->getAdapter()->quoteInto('id = ?', $arraySusBen['id']);
        //$newVal = $arraySusBen['cupon_consumido'] + $aumento;
        $newVal = $arraySusBen['cupon_consumido'];
        $modelSusBen->update(
            array('cupon_consumido'=>$newVal), 
            $where
        );
        self::actualizarCuponConsumidoBeneficio($idBen, $aumento);
    }
    
    public function actualizarCuponConsumidoBeneficio($idBen, $nro = 1)
    {
        $modelBeneficio = new Application_Model_Beneficio();
        $where = $modelBeneficio->getAdapter()->quoteInto('id = ?', $idBen);
//        $arrayBeneficio = $modelBeneficio->fetchRow($where);
        
        $modelCupon = new Application_Model_Cupon();
        $modelCupon->setBeneficio_id($idBen);
        $cant = $modelCupon->getCantCuponesByBenef(array('estado'=>'redimido'));
        $newVal = empty($cant)?0:$cant;
        
//        $newVal = $arrayBeneficio->ncuponesconsumidos + $nro;
        $modelBeneficio->update(array('ncuponesconsumidos' => $newVal), $where);
    }
    
    public function actualizarCuponGeneradoSuscriptorBeneficio($idSus, $idBen, $aumento=1)
    {
        $modelSusBen = new Application_Model_SuscriptorBeneficio();
        $arraySusBen = $modelSusBen->actualizarCuponRedimido($idSus, $idBen);
        
        $where = $modelSusBen->getAdapter()->quoteInto('id = ?', $arraySusBen['id']);
//        $newVal = $arraySusBen['cupon_generado'] + $aumento;
        $newVal = $arraySusBen['cupon_generado'];
        $modelSusBen->update(
            array('cupon_generado'=>$newVal), 
            $where
        );
    }
    
    public function insertSuscriptorBeneficio($idSus, $idBen)
    {
        $modelSusBen = new Application_Model_SuscriptorBeneficio();
        $sql = $modelSusBen->select()
            ->where("suscriptor_id = '".$idSus."' AND beneficio_id = '".$idBen."'");
        $rs = $modelSusBen->getAdapter()->fetchRow($sql);
        if (empty($rs)) {
            $modelSusBen->insert(
                array(
                    'suscriptor_id'=>$idSus,
                    'beneficio_id'=>$idBen,
                    'cupon_generado'=>'0',
                    'cupon_consumido'=>'0'
                )
            );
        }
    }

}
