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
class Application_Model_Perfil extends App_Db_Table_Abstract
{
    protected $_name = 'perfil';    
    protected $_nombre;
    protected $_id;
    
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
        return $this->_nombre;
    }

    public function setNombre($_nombre)
    {
        $this->_nombre = $_nombre;
    }
    
    public function getPerfiles()
    {
        $db = $this->getAdapter();
        $subsql = $db->select()->from(array('u'=>'usuario'), array('count(*)'))
                               ->where('p.id = u.perfil_id');
        
        $sql = $db->select()->from(
            array('p'=>$this->_name), 
            array('p.*','nrouser'=> new Zend_Db_Expr("($subsql)"))
        );
        
        /*$sql->join(
            array('u'=>'usuario'), 'u.perfil_id = p.id', array('nrouser'=> 'count(*)')
        );*/
         $sql->where("nivel = ?", '1');
         
        if(!empty($this->_nombre)):
            $this->_nombre = $this->getRemoveApostrophe($this->_nombre);
            $sql->where("nombre LIKE ?", '%'.$this->getNombre().'%');
        endif;
        $sql->group('p.id');
        //echo $sql->assemble(); exit;
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }
    
    public function getPerfilById()
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(array('p'=>$this->_name))
                            ->where('id = ?', $this->getId());
        $rs = $this->getAdapter()->fetchRow($sql);
        return $rs;
    }
    
    public static function getPerfilesUsuario($nivel=null)
    {
        $obj = new Application_Model_Perfil();
        $db = $obj->getAdapter();
        $sql = $db->select()->from($obj->_name, array('id', 'nombre'));
        if (!empty($nivel)) {
            $sql->where("nivel = ?",$nivel);
        }
        $rs = $db->fetchPairs($sql);
        return $rs;
    }
    
    public function validNameProfileWithId($namePerfil)
    {
        $namePerfil = strtolower(str_replace(' ', '', $namePerfil));
        $db = $this->getAdapter();
        $sql = $this->select()->from(array('p'=> $this->_name));
        $sql->where("LOWER(REPLACE(p.nombre,' ','')) like ?", $namePerfil);
        if (!empty($this->_id)) {
            $sql->where('id not in(?)', $this->getId());
        }
        //echo $sql;
        $rs = $db->fetchAll($sql);
        //var_dump($rs); exit;
        return empty($rs);
    }
}
