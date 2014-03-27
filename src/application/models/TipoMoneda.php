<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Alerta
 *
 * @author Computer
 */
class Application_Model_TipoMoneda extends App_Db_Table_Abstract
{
    protected $_name = 'tipo_moneda';

    public static function getTipoMoneda($criteria = array(), $getPairs = false)
    {
        $obj = new Application_Model_TipoMoneda();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from($obj->_name, array());
        if ( isset($criteria['estado']) ) {
            $sql = $sql->where('activo = ?', $criteria['estado']);
        }
        if ( isset($criteria['nombre']) ) {
            $sql = $sql->where("nombre LIKE ?", '%'.$criteria['nombre'].'%');
        }
        //$sql = $sql->order('id ASC');
        if ( $getPairs ) {
            $sql = $sql->columns(array('id', 'nombre'=>'abreviatura'));
            $rs = $db->fetchPairs($sql);
        } else {
            $sql = $sql->columns();
            $rs = $db->fetchAll($sql);
        }
        //echo $sql;exit;
        //var_dump($rs);exit;
        return $rs;
    }

}