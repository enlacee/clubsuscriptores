<?php

class Delta_0092 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
        protected $_desc = 'Agregando campos a la tabla detalle_tipo_promocion';
    
    public function up()
    {
        $sql = "ALTER TABLE `detalle_tipo_promocion` ADD `porcentaje_descuento` INT(4) NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `detalle_tipo_promocion` ADD `precio_regular` DECIMAL( 8, 2 ) DEFAULT NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `detalle_tipo_promocion` ADD `precio_suscriptor` DECIMAL( 8, 2 ) DEFAULT NULL;";
        $this->_db->query($sql);
//        $sql = "ALTER TABLE `detalle_tipo_promocion` ADD `ahorro` DECIMAL( 8, 2 ) NOT NULL ;";
//        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` ADD `tipo_redencion` enum('0','1') DEFAULT '0'".
                " COMMENT 'para realizar la forma de poder redimir 0 no cierra puerta 1 cierra puerta';";
        $this->_db->query($sql);
//        $sql = "INSERT INTO `opcion_perfil` (`opcion_id`, `perfil_id`, `activo`)
//            VALUES ('9', '4','1'),('10', '4','1'),('11', '4','1'),('16', '4','1'),
//                   ('9', '5','1'),('10', '5','1'),('11', '5','1'),('16', '5','1'),('17', '5','1');";
//        $this->_db->query($sql);
        return true;
    }
}