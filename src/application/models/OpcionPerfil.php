<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of OpcionPerfil
 *
 * @author e-solutions
 */
class Application_Model_OpcionPerfil extends App_Db_Table_Abstract
{
    protected $_name = 'opcion_perfil';
    protected $_opcionId;
    protected $_perfilId;
    
    public function getPerfil_id()
    {
        return $this->_perfilId;
    }
    
    public function setPerfil_id($_perfilId)
    {
        $this->_perfilId = $_perfilId;
    }
    
    public function getOpcion_id()
    {
        return $this->_opcionId;
    }
    
    public function setOpcion_id($_opcionId)
    {
        $this->_opcionId = $_opcionId;
    }
    
    public function getOpcionesByPerfil($filter = 1, $activo = 1)
    {
        $db = $this->getAdapter();
        $sql = 
            $db->select()->from(
                array('op' => $this->_name), 
                array('activo', 'perfil_id')
            )
            ->where('op.perfil_id = ?', $this->getPerfil_id())
            ->order('o.orden');
        if (!empty($filter)) {
            $sql->where('op.activo = ?', $activo);
        }
        $sql->join(
            array('o'=>'opcion'), 'o.id = op.opcion_id', 
            array('id', 'nombreop', 'controlador', 'modulo_id')
        );
        $sql->join(
            array('m'=>'modulo'), 'm.id = o.modulo_id', array('nombremod')
        );
        
        //echo $sql->assemble(); exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
}
