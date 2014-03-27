<?php

class Delta_0074 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Tabla para tener tipo de moneda asociado a los tipos de descuento';

    public function up()
    {
        $sql = "ALTER TABLE `detalle_tipo_promocion` ADD `tipo_moneda_id` TINYINT NOT NULL DEFAULT 1;";
        $this->_db->query($sql);
        $sql = "DROP TABLE IF EXISTS `tipo_moneda`;";
        $this->_db->query($sql);
        $sql = "CREATE TABLE IF NOT EXISTS tipo_moneda (
                id TINYINT NOT NULL AUTO_INCREMENT ,
                abreviatura VARCHAR(15) NOT NULL ,
                descripcion VARCHAR(45) NOT NULL ,
                estado TINYINT(1) NULL DEFAULT 1 ,
                PRIMARY KEY ( id ) ,
                UNIQUE INDEX `abreviatura_UNIQUE` (`abreviatura` ASC) 
                )ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $this->_db->query($sql);
        $sql = "INSERT INTO `tipo_moneda` (`id`,`abreviatura`,`descripcion`,`estado`)
            VALUES ('1','S/.','Nuevo Sol', '1'),('2','US$','Dolar', '1');";
        $this->_db->query($sql);
        return true;
    }

}
