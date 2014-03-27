<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Establecimiento
 *
 * @author Usuario
 */
class Application_Model_Establecimiento extends App_Db_Table_Abstract
{

    //put your code here
    protected $_name = 'establecimiento';

    function getEstablecimientoPorUsuario($_usuarioId)
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(array('estu' => 'establecimiento_usuario'), array());
        $sql->join(
            array('est' => 'establecimiento'), 'est.id = estu.establecimiento_id', array('*')
        );
        $sql->join(
            array('tipest' => 'tipo_establecimiento'), 
            'tipest.id = est.tipo_establecimiento_id', 
            array('tipestnombre' => 'nombre')
        );
        $sql->where('estu.usuario_id = ?', $_usuarioId);
        //echo $sql; exit;
        return $db->fetchRow($sql);
    }

    public static function getEstablecimientos($criteria = array(), $getPairs = false)
    {
        $obj = new Application_Model_Establecimiento();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from($obj->_name, array());
        if ( isset($criteria['estado']) ) {
            $sql = $sql->where('activo = ?', $criteria['estado']);
        }
        if ( isset($criteria['nombre']) ) {
            $sql = $sql->where("nombre LIKE ?", '%'.$criteria['nombre'].'%');
        }
        $sql = $sql->order('nombre ASC');
        if ( $getPairs ) {
            $sql = $sql->columns(array('id', 'nombre'));
            $rs = $db->fetchPairs($sql);
        } else {
            $sql = $sql->columns();
            $rs = $db->fetchAll($sql);
        }
        return $rs;
    }

    public static function esEstablecimientoActivo($id)
    {
        $obj = new Application_Model_Establecimiento();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from($obj->_name)
            ->where('id = ?', $id)
            ->where('activo = 1')
            ->limit(1);
        $rs = $db->fetchRow($sql);
        return (bool) $rs;
    }

    public static function getEstablecimiento($id)
    {
        $obj = new Application_Model_Establecimiento();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('est' => $obj->_name))
            ->where('est.id = ?', $id)
            ->limit(1);
        $sql->join(
            array('tipest' => 'tipo_establecimiento'), 
            'tipest.id = est.tipo_establecimiento_id', 
            array('tipestnombre' => 'nombre')
        );
        $rs = $db->fetchRow($sql);
        return $rs;
    }
    
    public static function getUsuariosEstablecimientos($criteria)
    {
        $col = $criteria['col'] == '' ? 
            new Zend_Db_Expr("CONCAT(a.apellido_paterno, ' ', a.apellido_materno)") : $criteria['col'];
        $ord = $criteria['ord'] == '' ? 'DESC' : $criteria['ord'];
        
        $obj = new Application_Model_Establecimiento();
        $sql = $obj->getAdapter()
            ->select()
            ->from(array('eu'=>'establecimiento_usuario'), array())
            ->joinInner(
                array('u'=>'usuario'), 'u.id = eu.usuario_id', array('u.id', 'u.email', 'u.activo')
            )->joinInner(
                array('a'=>'administrador'), 
                'a.usuario_id = eu.usuario_id',
                array(
                    'a.nombres',
                    'apellidos' => new Zend_Db_Expr("CONCAT(a.apellido_paterno, ' ', a.apellido_materno)"), 
                    'a.numero_documento'
                )
            )
            ->where('eu.establecimiento_id = ? ', $criteria['idEst']);
        if (isset($criteria['estado'])) {
            $sql = $sql->where('u.activo = ?', $criteria['estado']);
        }
        if (isset($criteria['nombre'])) {
            $sql = $sql->where(
                'a.nombres LIKE '.'"%'.$criteria['nombre'].
                '%" OR a.apellido_paterno LIKE "%'.$criteria['nombre'].
                '%" OR a.apellido_materno LIKE "%'.$criteria['nombre'].'%"'
            );
        }
        $sql = $sql->order(sprintf('%s %s', $col, $ord));
        return $sql;
    }

    public function getEstablecimientosPaginator($criteria = array())
    {
        $nropag = 5;
        $paginator = Zend_Paginator::factory(
            Application_Model_Establecimiento::getEstablecimientos($criteria)
        );
        return $paginator->setItemCountPerPage($nropag);
    }

    public function getUsuariosPaginator($criteria = array())
    {
        $nropag = 5;
        $paginator = Zend_Paginator::factory(
            Application_Model_Establecimiento::getUsuariosEstablecimientos($criteria)
        );
        return $paginator->setItemCountPerPage($nropag);
    }

    public static function validacionRuc($value)
    {
        $options = func_get_args();
        $id = $options[2];
        $e = new Application_Model_Establecimiento();
        $sql = $e->select()
                ->from('establecimiento', 'id')
                ->where('ruc = ?', $value)
                ->limit('1');
        if ($id) {
            $sql = $sql->where('id != ?', $id);
        }
        $sql = $sql->limit('1');

        $r = $e->getAdapter()->fetchOne($sql);
        return ! (bool) $r;
    }
    
    public function validacionNRuc($value)
    {
        $options = func_get_args();
        $idUsuario = $options[2];
        $db = $this->getAdapter();
        $sql = $db->select()
                ->from('establecimiento', 'id')
                ->where('ruc = ?', $value);
        if ($idUsuario) {
            $sql = $sql->where('id != ?', $idUsuario);
        }
        $sql = $sql->limit('1');
        $r = $db->fetchOne($sql);
        return !(bool) $r;
    }
    
    public static function updateNumeroUsuarioEstablecimiento($id)
    {
        $obj = new Application_Model_Establecimiento();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('e' => $obj->_name))
            ->join(array('eu' => 'establecimiento_usuario'), 'e.id = eu.establecimiento_id')
            ->join(array('u' => 'usuario'), 'eu.usuario_id = u.id')
            ->where('e.id = ?', $id);
        $rs = $db->fetchAll($sql);
        $totalUsuarios = count($rs);
        $where = $db->quoteInto('id = ?', $id);
        $obj->update(array('numero_usuarios' => $totalUsuarios), $where);
        return $totalUsuarios;
    }

    public static function updateNumeroBeneficiosEstablecimiento($id)
    {
        $obj = new Application_Model_Establecimiento();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('b' => 'beneficio'))
            ->where('b.establecimiento_id = ?', $id);
        $rs = $db->fetchAll($sql);
        $totalBeneficios = count($rs);
        $where = $db->quoteInto('id = ?', $id);
        $obj->update(array('numero_beneficio' => $totalBeneficios), $where);
        return $totalBeneficios;
    }
    
    public static function getEstablecimientoByNumeroTelefonico($numero) 
    {
        $obj = new Application_Model_Establecimiento();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(
                array('e' => $obj->_name), 
                array('id' => 'e.id', 'nombre' => 'e.nombre', 'activo' => 'e.activo')
            )
            ->join(
                array('nt' => 'numero_telefonico_establecimiento'), 
                'nt.establecimiento_id = e.id', 
                array(
                    'numero' => 'nt.numero_telefonico', 
                    'operador' => 'nt.operador', 
                    'nt_activo' => 'nt.activo'
                )
            )
            ->where('nt.numero_telefonico = ?', $numero)
            ->limit(1);
        return $db->fetchRow($sql);
    }
}