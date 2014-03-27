<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of SuscriptorDireccion
 *
 * @author Favio Condori
 */
class Application_Model_SuscriptorDireccion extends App_Db_Table_Abstract
{
    protected $_name = 't_suscriptor_direccion';
    
    public static function getSuscriptorDistrito($codigo, $distrito)
    {
        $obj = new Application_Model_SuscriptorDireccion();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('d' => $obj->_name))
            ->where('cod_entesuscriptor = ?', $codigo)
            ->where('cod_distrientrega = ?', $distrito)
            ->where('estado = ?', 1);
//        echo $sql->assemble();
        return $db->fetchRow($sql);
    }
    
    public static function getSuscriptorDistritoByCodigo($codigo)
    {
        $obj = new Application_Model_SuscriptorDireccion();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('d' => $obj->_name))
            ->where('cod_entesuscriptor = ?', $codigo)
            ->where('estado = ?', 1);
//        echo $sql->assemble();
        return $db->fetchRow($sql);
    }
    
}