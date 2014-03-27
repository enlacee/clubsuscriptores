<?php

class Delta_0093 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
        protected $_desc = 'Agregando campos porcentaje_descuento,precio_regular,precio_suscriptor y tipo_redencion
            a la tabla cupon';
    
    public function up()
    {
        $sql = "ALTER TABLE `cupon` CHANGE `monto_consumido` `precio_regular` DECIMAL( 8, 2 ) DEFAULT NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `cupon` CHANGE `monto_cobrado` `precio_suscriptor` DECIMAL( 8, 2 ) DEFAULT NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `cupon` ADD `porcentaje_descuento` INT(4) NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `cupon` ADD `tipo_redencion` enum('0','1') DEFAULT '0'".
                " COMMENT 'para realizar la forma de poder redimir 0 no cierra puerta 1 cierra puerta';";
        $this->_db->query($sql);
        $sql = "UPDATE beneficio SET tipo_redencion='1' WHERE tipo_beneficio_id=3;";
        $this->_db->query($sql);
        $sql = "UPDATE cupon SET tipo_redencion='1' WHERE beneficio_id IN (SELECT id FROM beneficio WHERE tipo_beneficio_id=3);";
        $this->_db->query($sql);
        return true;
    }
}