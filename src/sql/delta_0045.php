<?php

/**
 * Description of delta_0045
 *
 * @author FCJ
 */
class Delta_0045 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campos hash y origen en la tabla suscriptor';
    
    public function up()
    {
        $sql = "ALTER TABLE suscriptor ADD COLUMN `hash` VARCHAR(50) NULL AFTER `slug`;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `suscriptor` ADD `origen` ENUM( 'web', 'cron' ) NOT NULL DEFAULT 'web'";
        $this->_db->query($sql);
        return true;
    }
}
