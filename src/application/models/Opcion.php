<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Opcion
 *
 * @author e-solutions
 */
class Application_Model_Opcion extends App_Db_Table_Abstract
{
    protected $_name = 'opcion';
    protected $_moduloId;

    public function getModulo_id()
    {
        return $this->_moduloId;
    }

    public function setModulo_id($_moduloId)
    {
        $this->_moduloId = $_moduloId;
    }

    public function getOpcionesByModulo()
    {
        $db = $this->getAdapter();
        $sql =
            $db->select()->from(
                array('o' => $this->_name), array('o.*', 'activo' => new Zend_Db_Expr("0"))
            )
            ->where('o.modulo_id = ?', $this->getModulo_id())
            ->order('o.orden');

        //echo $sql->assemble(); exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }

    public static function getOpcionesPaginator($modulo)
    {
        $obj = new Application_Model_Opcion();
        $nropaginasrelacionados
            = empty($obj->_config->admin->opciones->nropaginas) ? 5 : $obj->_config->admin->opciones->nropaginas;
        $paginator = Zend_Paginator::factory($obj->getOpcionesByFilterModulo($modulo));
        return $paginator->setItemCountPerPage($nropaginasrelacionados);
    }

    public function getOpcionesByFilterModulo($modulo = null)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('o' => $this->_name))
            ->join(array('m' => 'modulo'), 'o.modulo_id = m.id', array('m_id' => 'm.id', 'modulo' => 'nombremod'));
        if (!empty($modulo))
            $sql = $sql->where('o.modulo_id = ?', $modulo);
        return $db->fetchAll($sql);
    }

    public function getOpcionByID($id)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('o' => $this->_name))
            ->join(array('m' => 'modulo'), 'm.id = o.modulo_id', array('modulo' => 'm.nombremod'))
            ->where('o.id = ?', $id)
            ->limit(1);
        return $db->fetchRow($sql);
    }

}
