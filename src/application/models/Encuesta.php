<?php

class Application_Model_Encuesta extends App_Db_Table_Abstract
{

    protected $_name = 'encuesta';
    //campos de la tabla
    protected $_id;
    protected $_creadoPor;
    protected $_nombre;
    protected $_pregunta;
    protected $_fechaRegistro;
    protected $_fechaInicio;
    protected $_fechaFin;
    protected $_activo;
    protected $_numeroOpciones;
    protected $_numeroRespuestas;
    
    const ESTADO_VIGENTE = 1;
    const ESTADO_NO_VIGENTE = 0;
    
    public function __construct($_id = NULL)
    {
        // TODO Auto-generated method stub
        parent::__construct();
        $this->_id = $_id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($_id)
    {
        $this->_id = $_id;
    }

    public function getCreado_por()
    {
        return $this->_creadoPor;
    }

    public function getNombre()
    {
        return $this->_nombre;
    }

    public function getPregunta()
    {
        return $this->_pregunta;
    }

    public function getFecha_registro()
    {
        return $this->_fechaRegistro;
    }

    public function getFecha_inicio()
    {
        return $this->_fechaInicio;
    }

    public function getFecha_fin()
    {
        return $this->_fechaFin;
    }

    public function getActivo()
    {
        return $this->_activo;
    }

    public function getNumero_opciones()
    {
        return $this->_numeroOpciones;
    }

    public function getNumero_respuestas()
    {
        return $this->_numeroRespuestas;
    }

    public function setCreado_por($_creadoPor)
    {
        $this->_creadoPor = $_creadoPor;
    }

    public function setNombre($_nombre)
    {
        $this->_nombre = $_nombre;
    }

    public function setPregunta($_pregunta)
    {
        $this->_pregunta = $_pregunta;
    }

    public function setFecha_registro($_fechaRegistro)
    {
        $this->_fechaRegistro = $_fechaRegistro;
    }

    public function setFecha_inicio($_fechaInicio)
    {
        $this->_fechaInicio = $_fechaInicio;
    }

    public function setFecha_fin($_fechaFin)
    {
        $this->_fechaFin = $_fechaFin;
    }

    public function setActivo($_activo)
    {
        $this->_activo = $_activo;
    }

    public function setNumero_opciones($_numeroOpciones)
    {
        $this->_numeroOpciones = $_numeroOpciones;
    }

    public function setNumero_respuestas($_numeroRespuestas)
    {
        $this->_numeroRespuestas = $_numeroRespuestas;
    }

    public function getEncuestas()
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(array('enc'=>$this->_name));
        if($this->_activo!='')
            $sql->where('activo = ?', $this->getActivo());
        
        if(!empty($this->_nombre)):
            $this->_nombre = $this->getRemoveApostrophe($this->_nombre);
            $sql->where("CONCAT(nombre,' ',pregunta) LIKE ?", '%'.$this->getNombre().'%');
        endif;
        $sql->where('elog = ?', 0); //agregado v1.5
        //$sql->where('CURRENT_DATE() BETWEEN fecha_inicio AND fecha_fin');
        //$rs = $this->getAdapter()->fetchAll($sql);
        return $sql;
    }

    public function getEncuestaActual()
    {
        $sql = $this->select()->from($this->_name)
            ->limit(1, 0)
            ->where('activo = ?', 1)
            ->where('elog = ?', 0) //agregado v1.5
            //->where('CURRENT_DATE() BETWEEN fecha_inicio AND fecha_fin')
            ->order('fecha_registro desc');
        //echo $sql; exit;
        $rs = $this->getAdapter()->fetchRow($sql);
        $this->_id = (!empty($rs) ? $rs['id'] : '');
        return $rs;
    }

    public function getEncuesta()
    {
        $sql = $this->select()->from($this->_name)->where('id = ?', $this->getId());
        $rs = $this->getAdapter()->fetchRow($sql);
        return $rs;
    }
    
    public function setIncrementNroRespuestasByOpEncuestaId($_opcionEncuestaId = '')
    {
        if ( !empty($_opcionEncuestaId) ) {
            $sql = $this->select()
                        ->from(array('e'=>$this->_name), array('e.numero_respuestas','e.id'));
            $sql->join(array('oe'=>'opcion_encuesta'), 'oe.encuesta_id = e.id', array())
                ->where('oe.id = ?', $_opcionEncuestaId);
            //echo $sql; exit;
            $rs = $sql->getAdapter()->fetchRow($sql);
            if ( !empty($rs) ) {
                $data['numero_respuestas'] = $rs['numero_respuestas'] + 1;
                $where = $this->getAdapter()->quoteInto('id = ?', $rs['id']);
                $this->update($data, $where);
            }
        }
    }    
}
