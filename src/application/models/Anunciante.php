<?php

/**
 * Description of Anunciante
 *
 * @author Ander
 */
class Application_Model_Anunciante extends App_Db_Table_Abstract
{
    
    protected $_name = 'anunciante';

    public static function getAnunciantes($criteria = array(), $getPairs = false)
    {
        $obj = new Application_Model_Anunciante();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from($obj->_name, array())
            ->where('elog = ?', 0);
        if ( isset($criteria['estado']) ) {
            $sql = $sql->where('activo = ?', $criteria['estado']);
        }
        if ( isset($criteria['nombre']) ) {
            $sql = $sql->where("razon_social LIKE ?", '%'.$criteria['nombre'].'%');
        }
        $sql = $sql->order('razon_social ASC');
        if ( $getPairs ) {
            $sql = $sql->columns(array('id', 'razon_social'));
            $rs = $db->fetchPairs($sql);
        } else {
            $sql = $sql->columns();
            $rs = $db->fetchAll($sql);
        }
        //echo $sql;exit;
        return $rs;
    }
    
    public function getStatusAnuncianteById($id)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('a' => $this->_name), 
                   array('a.razon_social', 'a.activo',
                         'nroasociados' => new Zend_Db_Expr(
                            '(SELECT COUNT(1) FROM beneficio b
                              WHERE b.anunciante_id=a.id)'
                        )
                    ))
            ->where('a.id = ?', $id);
        //echo $sql->assemble();exit;
        $rsb = $db->fetchRow($sql);
        
        return $rsb;
    }
    
    public function getAnunciante($id)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('est' => $this->_name))
            ->where('est.id = ?', $id)
            ->limit(1);
        $rs = $db->fetchRow($sql);
        return $rs;
    }

    public function getAnunciantePaginator($criteria = array())
    {
        $nropag = 5;
        $paginator = Zend_Paginator::factory(
            Application_Model_Anunciante::getAnunciantes($criteria)
        );
        return $paginator->setItemCountPerPage($nropag);
    }
    
    public static function validacionRuc($value)
    {
        $options = func_get_args();
        $id = $options[2];
        $db = new Application_Model_Anunciante();
        $sql = $db->select()
                ->from($db->_name, 'id')
                ->where('ruc = ?', $value)
                ->where('elog != ?', '1')
                ->limit('1');
        if ($id) {
            $sql = $sql->where('id != ?', $id);
        }
        $sql = $sql->limit('1');
        //echo $sql->assemble();exit;
        $r = $db->getAdapter()->fetchOne($sql);
        return ! (bool) $r;
    }
    
//    public function validacionNRuc($value)
//    {
//        $options = func_get_args();
//        $id = $options[2];
//        $db = $this->getAdapter();
//        $sql = $db->select()
//                ->from($this->_name, 'id')
//                ->where('elog != ?', '1')
//                ->where('ruc = ?', $value);
//        if ($id) {
//            $sql = $sql->where('id != ?', $id);
//        }
//        $sql = $sql->limit('1');
//        //echo $sql->assemble();exit;
//        $r = $db->fetchOne($sql);
//        return !(bool) $r;
//    }
    
}