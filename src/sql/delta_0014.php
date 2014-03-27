<?php

class Delta_0014 extends App_Migration_Delta
{
    protected $_autor = "Favio Condori";
    protected $_desc = "Cambiar a autoincremente ID de tablas con data externa";

    public function up()
    {

        /**
         * LOS CAMBIOS SE APLICARON DIRECTAMENTE SOBRE EL SCRIPT DE CREACION DE LA BASE DE DATOS
         */
//        $sql = "ALTER TABLE `t_hjsuscriptor` CHANGE `id_hjsuscriptor` `id_hjsuscriptor` INT( 11 ) NOT NULL AUTO_INCREMENT";
//        $this->_db->query($sql);
//        $sql = "ALTER TABLE `t_suscriptor_direccion` CHANGE `Id_Dir_Suc` `id_Dir_Susc` INT( 11 ) NOT NULL AUTO_INCREMENT";
//        $this->_db->query($sql);
//        $sql = "ALTER TABLE `t_suscriptor_producto` CHANGE `Id_Prod_Susc` `id_Prod_Susc` INT( 11 ) NOT NULL AUTO_INCREMENT";
//        $this->_db->query($sql);
        return true;
    }

}