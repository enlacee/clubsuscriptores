<?php

/**
 * Description of delta_0057
 *
 * @author phpauldj
 */
class Delta_0057 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Cambiando el tipo de dato del campo informacion_adicional en beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` CHANGE `informacion_adicional` `informacion_adicional` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
        $this->_db->query($sql);
        return true;
    }
}
