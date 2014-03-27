<?php

/**
 * Description of delta_0059
 *
 * @author phpauldj
 */
class Delta_0072 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregar nuevo campo activo en la tabla detalle_tipo_promocion';
    
    public function up()
    {
        $sql = "ALTER TABLE `detalle_tipo_promocion` ADD `activo` TINYINT(1) NULL DEFAULT 1;";
        $this->_db->query($sql);
        return true;
    }
}
