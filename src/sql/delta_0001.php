<?php

/**
 * Description of delta_0001
 *
 * @author DjTabo
 */
class Delta_0001 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregando campo es_suscriptor, para diferenciar datos de un suscriptor y usuario registrado';
    
    public function up()
    {
        $sql = "ALTER TABLE suscriptor ADD es_suscriptor TINYINT(1) NOT NULL DEFAULT 0";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `cupon` CHANGE `fecha_vigencia` `fecha_fin_vigencia` DATETIME NULL";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `cupon` ADD `fecha_inicio_vigencia` DATETIME NULL AFTER fecha_emision";
        $this->_db->query($sql);
        return true;
    }
}