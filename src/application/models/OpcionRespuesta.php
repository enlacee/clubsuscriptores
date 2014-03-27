<?php

class Application_Model_OpcionRespuesta extends App_Db_Table_Abstract
{

    protected $_name = 'opcion_respuesta';
    protected $_id;
    protected $_opcionEncuestaId;
    protected $_fechaVoto;
    protected $_ipVotante;
    protected $_encuestaId;

    public function getEncuesta_id()
    {
        return $this->_encuestaId;
    }

    public function setEncuesta_id($_encuestaId)
    {
        $this->_encuestaId = $_encuestaId;
    }

    public function __construct()
    {
        // TODO Auto-generated method stub
        parent::__construct();
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getOpcion_encuesta_id()
    {
        return $this->_opcionEncuestaId;
    }

    public function getFecha_voto()
    {
        return $this->_fechaVoto;
    }

    public function getIp_votante()
    {
        return $this->_ipVotante;
    }

    public function setId($_id)
    {
        $this->_id = $_id;
    }

    public function setOpcion_encuesta_id($_opcionEncuestaId)
    {
        $this->_opcionEncuestaId = $_opcionEncuestaId;
    }

    public function setFecha_voto($_fechaVoto)
    {
        $this->_fechaVoto = $_fechaVoto;
    }

    public function setIp_votante($_ipVotante)
    {
        $this->_ipVotante = $_ipVotante;
    }

    public function getResultadoEncuesta()
    {
        $db = $this->getAdapter();
        $sql = 
            $db->select()->from(
                array('opr' => $this->_name), 
                array('votos' => new Zend_Db_Expr('count(opr.id)'))
            )
            ->joinRight(
                array('oe' => 'opcion_encuesta'), 'opr.opcion_encuesta_id = oe.id', 
                array('*')
            )
            ->group('oe.id')
            ->where('oe.encuesta_id = ?', $this->getEncuesta_id())
            ->order('votos desc');
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }

    public function getTotalVotosEncuesta()
    {
        $db = $this->getAdapter();
        $sql = 
               $db->select()->from(
                   array('opr' => $this->_name), 
                   array('tvotos' => new Zend_Db_Expr('count(oe.id)'))
               )
               ->joinLeft(
                   array('oe' => 'opcion_encuesta'), 'opr.opcion_encuesta_id = oe.id', 
                   array()
               )
               ->group('oe.encuesta_id')
               ->where('encuesta_id = ?', $this->getEncuesta_id());
        $rs = $db->fetchRow($sql);
        return $rs;
    }

    public function getExisteVotacion()
    {
        $sql = $this->select()->from($this->_name, array('cant' => 'count(*)'))
                    ->where('ip_votante = ?', $this->getIp_votante());
        //echo $sql; exit;
        $rs = $this->getAdapter()->fetchRow($sql);
        return (int) $rs['cant'];
    }

}
