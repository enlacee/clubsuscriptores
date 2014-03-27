<?php

class Application_Model_TipoBeneficio extends App_Db_Table_Abstract
{

    protected $_name = 'tipo_beneficio';
    protected $_id;
    
    const TIPO_ALL = '0';
    
    public function getId()
    {
        return $this->_id;
    }

    public function setId($_id)
    {
        $this->_id = $_id;
    }

    public function getNombre()
    {
        $sql = $this->select()->from($this->_name, array('nombre'))
                              ->where('id = ?', $this->getId());
        $rs = $this->getAdapter()->fetchRow($sql);
        return (!empty($rs)?$rs['nombre']:'');
    }
    
    public static function getTiposBeneficio($activos = false, $getPairs = false)
    {
        $obj = new Application_Model_TipoBeneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from($obj->_name, array());
        if ($activos)
            $sql = $sql->where('activo = 1');
        if ($getPairs) {
            $sql = $sql->columns(array('id', 'nombre'));
            $rs = $db->fetchPairs($sql);
        } else {
            $sql = $sql->columns();
            $rs = $db->fetchAll($sql);
        }
        return $rs;
    }
    
    public static function getTipoBeneficio($id)
    {
        $obj = new Application_Model_TipoBeneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from($obj->_name)
            ->where('id = ?', $id)
            ->limit(1);
        $rs = $db->fetchRow($sql);
        return $rs;
    }
    
    public static function esTipoBeneficioActivo($id)
    {
        $obj = new Application_Model_TipoBeneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from($obj->_name)
            ->where('id = ?', $id)
            ->where('activo = 1')
            ->limit(1);
        $rs = $db->fetchRow($sql);
        return (bool) $rs;
    }
}
