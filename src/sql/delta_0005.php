<?php

/**
 * Description of delta_0005
 *
 * @author DjTabo
 */
class Delta_0005 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregando el campo nombre a la tabla usuario';
    
    public function up()
    {
        /*$sql = "ALTER TABLE `usuario` ADD `nombre` VARCHAR(80) NULL AFTER id";
        $this->_db->query($sql);*/
        $sql = "ALTER TABLE `cupon` CHANGE `estado` `estado` enum('generado',".
            "'consumido','redimido','conciliado') DEFAULT NULL";
        $this->_db->query($sql);
        return true;
    }
}
