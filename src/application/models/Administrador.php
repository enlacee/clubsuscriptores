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
class Application_Model_Administrador extends App_Db_Table_Abstract
{
    protected $_name = 'administrador';
    
    public function getAdministradorxId ($idUsu)
    {
        $sql = $this->getAdapter()
            ->select()
            ->from(
                array('a' => $this->_name), 
                array(
                    'a.nombres', 
                    'apellidos' => new Zend_Db_Expr("CONCAT(a.apellido_paterno, ' ', a.apellido_materno)"),
                    'a.apellido_paterno',
                    'a.apellido_materno',
                    'id_admin' =>'a.id'
                )
            )
            ->joinInner(
                array('u' => 'usuario'),
                $this->getAdapter()->quoteInto('u.id = a.usuario_id and u.id = ?', $idUsu),
                array('u.email', 'u.activo', 'u.rol')
            )                
            ->joinleft(
                array('p' => 'perfil'), 
                'u.perfil_id = p.id',
                array('perfil_id' => 'p.id','padre_perfil_id' => 'p.padre_perfil_id','nivel' => 'p.nivel')
            );
        //echo $sql;exit;
        return $this->getAdapter()->fetchRow($sql);
    }
    
    public static function getUsuariosByEstablecimientoIDPaginator($id)
    {
        $obj = new Application_Model_Administrador();
        $nropaginasrelacionados = empty(
            $obj->_config->establecimiento->usuario->nropaginas
            )? 5: $obj->_config->establecimiento->usuario->nropaginas;
        $paginator = Zend_Paginator::factory($obj->getUsuariosByEstablecimientoID($id));
        return $paginator->setItemCountPerPage($nropaginasrelacionados);
    }
    
    public function getUsuariosByEstablecimientoID($id)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('a' => $this->_name))
            ->join(array('u' => 'usuario'), 'a.usuario_id = u.id AND u.rol = "establecimiento"')
            ->join(array('eu' => 'establecimiento_usuario'), 'eu.usuario_id = u.id')
            ->where('eu.establecimiento_id = ?', $id);
        
        return $db->fetchAll($sql);
    }
}
