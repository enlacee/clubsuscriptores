<?php

/**
 * Description of delta_0051
 *
 * @author FCJ
 */
class Delta_0051 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar tipo de documento RUC y PAS';
    
    public function up()
    {
        $sql = "ALTER TABLE `suscriptor` CHANGE `tipo_documento` `tipo_documento` ENUM( 'DNI', 'CEX', 'RUC', 'PAS' ) NULL DEFAULT NULL";
        $this->_db->query($sql);
        return true;
    }
}
