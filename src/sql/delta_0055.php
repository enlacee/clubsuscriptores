<?php

/**
 * Description of delta_0055
 *
 * @author FCJ
 */
class Delta_0055 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campo DSCTO_SUSCRIPTOR_VIP en la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `DSCTO_SUSCRIPTOR_VIP` DECIMAL( 18, 10 ) NULL DEFAULT NULL";
        $this->_db->query($sql);
        return true;
    }
}
