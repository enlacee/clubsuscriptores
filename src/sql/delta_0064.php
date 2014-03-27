<?php

/**

 * Description of delta_0064
 *
 * @author phpauldj
 */
class Delta_0064 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregar campo elog para eliminar logicamente una encuesta registrada';
    
    public function up()
    {
        $sql = "ALTER TABLE encuesta ADD elog TINYINT(1) NOT NULL DEFAULT 0";
        $this->_db->query($sql);
        
        return true;
    }
}