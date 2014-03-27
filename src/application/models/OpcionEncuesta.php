<?php

class Application_Model_OpcionEncuesta extends App_Db_Table_Abstract
{

    protected $_name = 'opcion_encuesta';
    protected $_encuestaId;
    protected $_opcion;

    public function __construct(Application_Model_Encuesta $objencuesta = NULL)
    {
        // TODO Auto-generated method stub
        parent::__construct();
        if ( !empty($objencuesta) ) {
            $this->_encuestaId = $objencuesta->getId();
        }
    }

    public function getEncuesta_id()
    {
        return $this->_encuestaId;
    }

    public function getOpcion()
    {
        return $this->_opcion;
    }

    public function setEncuesta_id($_encuestaId)
    {
        $this->_encuestaId = $_encuestaId;
    }

    public function setOpcion($_opcion)
    {
        $this->_opcion = $_opcion;
    }

    public function getOpciones()
    {
        $sql = $this->select()->from($this->_name)
                              ->where('encuesta_id = ?', $this->getEncuesta_id());
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }
    
    public function setIncrementNroRespuestasByOp($id = '')
    {
        if ( !empty($id) ) {
            $sql = $this->select()
                        ->from(array('oe'=>$this->_name), array('nrorespuesta','id'))
                        ->where('oe.id = ?', $id);
            //echo $sql; exit;
            $rs = $sql->getAdapter()->fetchRow($sql);
            if ( !empty($rs) ) {
                $data['nrorespuesta'] = $rs['nrorespuesta'] + 1;
                $where = $this->getAdapter()->quoteInto('id = ?', $rs['id']);
                $this->update($data, $where);
            }
        }
    }
    
    public function getResultadoEncuesta()
    {
        $db = $this->getAdapter();
        $sql = 
            $db->select()->from(
                array('oe' => $this->_name), 
                array('*','votos'=>'nrorespuesta')
            )
            ->where('oe.encuesta_id = ?', $this->getEncuesta_id())
            ->order('oe.nrorespuesta desc');
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }

    public function getTotalVotosEncuesta()
    {
        $db = $this->getAdapter();
        $sql = 
               $db->select()->from(
                   array('oe' => $this->_name), 
                   array('tvotos' => new Zend_Db_Expr('sum(oe.nrorespuesta)'))
               )
               ->group('oe.encuesta_id')
               ->where('oe.encuesta_id = ?', $this->getEncuesta_id());
        $rs = $db->fetchRow($sql);
        return $rs;
    }
    
    public function getOpcionesMasElegidas()
    {
        $db = $this->getAdapter();
        
        $subsql = $db->select()->from(array('oenc'=>$this->_name), array('max(nrorespuesta)'))
                               ->where('oenc.encuesta_id = ?', $this->getEncuesta_id());
        $sql = 
            $db->select()->from(
                array('oe' => $this->_name), 
                array('*','votos'=>'nrorespuesta')
            )
            ->where('oe.encuesta_id = ?', $this->getEncuesta_id())
            ->where('oe.nrorespuesta = (?)', $subsql)
            ->order('oe.nrorespuesta desc');
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
    
    public function getOpcionesMenosElegidas()
    {
        $db = $this->getAdapter();
        
        $subsql = $db->select()->from(array('oenc'=>$this->_name), array('min(nrorespuesta)'))
                               ->where('oenc.encuesta_id = ?', $this->getEncuesta_id());
        $sql = 
            $db->select()->from(
                array('oe' => $this->_name), 
                array('*','votos'=>'nrorespuesta')
            )
            ->where('oe.encuesta_id = ?', $this->getEncuesta_id())
            ->where('oe.nrorespuesta = (?)', $subsql)
            ->order('oe.nrorespuesta asc');
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
}
