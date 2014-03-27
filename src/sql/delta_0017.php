<?php

/**
 * Description of delta_0017
 *
 * @author JFlorian
 */
class Delta_0017 extends App_Migration_Delta
{
    protected $_autor = 'Julio Florian';
    protected $_desc = 'Aumento del campo numero de beneficio  en establecimiento';
    
    public function up()
    {
        $sql = "ALTER TABLE establecimiento ADD numero_beneficio INT( 11 ) 
        	NULL DEFAULT NULL COMMENT 'indica el numero de beneficios que tiene el establecimiento' AFTER numero_usuarios ";
        $this->_db->query($sql);
        return true;
    }
}
