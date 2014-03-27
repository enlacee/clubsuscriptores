<?php

class Application_Model_EncuestaSuscriptor extends App_Db_Table_Abstract
{

    protected $_name = 'encuesta_suscriptor';
    
    public function __construct($_id = NULL)
    {
        // TODO Auto-generated method stub
        parent::__construct();
        $this->_id = $_id;
    }
    
    /**
     * 
     * @param int $id
     * @return  int $rows[0]->cantidad (Debuelve 1 o 0) 
     * @todo identificamos is el suscriptor hizo la encuesta
     */
    
    public function getEncuestaRealizada($id){       
        $select = $this->select();
        //$select->from($this->_name, array('count(*) as cantidad'));
        $select->from($this->_name,array('count(*) as cantidad'));
        $select->where("suscriptor_id =".(int)$id);
        $rows = $this->fetchAll($select);
        return $rows[0]->cantidad;
    }
    
    /**
     * 
     * @param array $data
     * @return  return (respuesta de datos que se grabaron)  
     * @todo agregamos la encuesta del suscriptor
     */
    public function addEncuesta($data){        
        return $this->insert($data);
    }
    
    /**
     * 
     * @param int $id
     * @return  int  
     * @todo agregamos la encuesta del suscriptor
     */
    public function getIdEncuesta($id){        
        $select = $this->select();
    	$select->from($this->_name, array('idencuesta'));
    	$select->where("suscriptor_id =".$id);    	
        return $this->fetchRow($select);
    }
    
}
