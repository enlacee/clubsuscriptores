<?php

class Application_Model_InteresBeneficioClup extends App_Db_Table_Abstract
{

    protected $_name = 'interes_beneficio_club';
    
    public function __construct($_id = NULL)
    {
        // TODO Auto-generated method stub
        parent::__construct();
        $this->_id = $_id;
    }
    
    /**
     * 
     * @param array $data
     * @return  return (respuesta de datos que se grabaron)  
     * @todo agregamos parte de la interes_beneficio_club
     */
    
    public function addInteresBeneficioClup($data){        
        return $this->insert($data);
    }    
    
}
