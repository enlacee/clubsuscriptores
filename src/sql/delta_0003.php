<?php

/**
 * Description of delta_0003
 *
 * @author DjTabo
 */
class Delta_0003 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregando campo abreviado en tabla tipo_beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `tipo_beneficio` ADD `abreviado` VARCHAR(15) NULL AFTER descripcion";
        $this->_db->query($sql);
        return true;
    }
}
