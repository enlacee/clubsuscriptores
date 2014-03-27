<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of DistritoEntrega
 *
 * @author Favio Condori
 */
class Application_Model_DistritoEntrega extends App_Db_Table_Abstract
{
    protected $_name = 'distrito_entrega';

    public static function getDistritosEntrega($pair = false)
    {
        $obj = new Application_Model_DistritoEntrega();
        $db = $obj->getAdapter();

        $columns = array();
        if ($pair) {
            $columns = array('cciudis', 'dciudis');
        } else {
            $columns = array('id', 'cciudis', 'dciudis', 'cod_ubigeo', 'sregact');
        }

        $sql = $db->select()
            ->from(array('s' => $obj->_name), $columns)
            ->order('dciudis');

        if ($pair) {
            return $db->fetchPairs($sql);
        } else {
            return $db->fetchAll($sql);
        }
    }

}
