<?php

class Delta_0091 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Seteando valores de campo estado y agregando campos a la tabla cupon';
    
    public function up()
    {
        $sql = "ALTER TABLE `cupon` CHANGE `estado` `estado` enum('generado',".
            "'redimido','conciliado','eliminado') DEFAULT NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `cupon` ADD `fecha_eliminacion` DATETIME NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `cupon` ADD `eliminado_por` INT(11) NULL;";
        $this->_db->query($sql);
        return true;
    }
}