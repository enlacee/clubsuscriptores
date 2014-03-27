<?php

class Delta_0011 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Actualizacion de tabla para la carga de datos externa';

    public function up()
    {
        /**
         * LOS CAMBIOS SE APLICARON DIRECTAMENTE SOBRE EL SCRIPT DE CREACION DE LA BASE DE DATOS
         */
//        $sql = "ALTER TABLE `t_actsuscriptor` ADD `est_sus` CHAR(2) NULL  DEFAULT NULL, ADD `fch_registro` DATETIME NULL DEFAULT NULL";
//        $this->_db->query($sql);
//        $sql = "ALTER TABLE `t_actsuscriptor` CHANGE `estado_reparto` `estado_reparto` CHAR(2) NULL DEFAULT NULL ";
//        $this->_db->query($sql);
//        $sql = "ALTER TABLE `t_actsuscriptor` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
//        $this->_db->query($sql);
//        $sql = "ALTER TABLE `t_hjsuscriptor` ADD `fec_registro` DATETIME NULL DEFAULT NULL";
//        $this->_db->query($sql);
        return true;
    }

}
