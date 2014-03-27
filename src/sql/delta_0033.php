<?php

/**
 * Description of delta_0001
 *
 * @author FCJ
 */
class Delta_0033 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregando campo invitacion a la tabla invitacion';
    
    public function up()
    {
        $sql = "ALTER TABLE invitacion ADD invitacion TEXT NULL DEFAULT NULL AFTER sexo";
        $this->_db->query($sql);
        return true;
    }
}
