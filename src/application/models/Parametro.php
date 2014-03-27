<?php

class Application_Model_Parametro extends App_Db_Table_Abstract
{

    protected $_name = 'parametro';
    protected $_id;
    protected $_tipo;
    
    public function getId()
    {
        return $this->_id;
    }

    public function setId($_id)
    {
        $this->_id = $_id;
    }
    
    public function getTipo()
    {
        return $this->_tipo;
    }

    public function setTipo($_tipo)
    {
        $this->_tipo = $_tipo;
    }
    
    public function getTiposParametro()
    {
        $db = $this->getAdapter();
        $sql = $db->select()->distinct()
                  ->from($this->_name, array('tipid'=>'tipo','tipo'))
                  ->order('tipo asc');
        $rs = $db->fetchPairs($sql);
        return $rs;
    }
    
    public function getParametros()
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from($this->_name);
        
        if (!empty($this->_tipo)) {
            $sql->where('tipo LIKE ?', '%'.$this->getTipo().'%');
        }
        if (!empty($this->_id)) {
            $sql->where('id = ?', $this->getId());
        }
        //echo $sql; exit;
        //$rs = $db->fetchAll($sql);
        return $sql;
    }
    
    public function getParametrosPorActualizar()
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from($this->_name)
            ->where('actualizar = ?', 1);
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
}
