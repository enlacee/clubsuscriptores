<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Gestor
 *
 * @author Computer
 */
class Application_Model_Modulo extends App_Db_Table_Abstract
{
    protected $_name = 'modulo';

    public static function getModulos()
    {
        $obj = new Application_Model_Modulo();
        $db = $obj->getAdapter();
        $sql = $db->select()->from(array('m' => $obj->_name));
        $rs = $obj->getAdapter()->fetchPairs($sql);
        return $rs;
    }

    public function getNombreModulo($id)
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(array('m' => $this->_name))
            ->where('id = ?', $id);
        $rs = $this->getAdapter()->fetchRow($sql);
        return !empty($rs) ? $rs['nombremod'] : '';
    }

}
