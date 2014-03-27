<?php

/**
 * Description of delta_0059
 *
 * @author fcj
 */
class Delta_0059 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campos de apellidos materno y paterno en la tabla administrador';
    
    public function up()
    {
        $sql = "ALTER TABLE `administrador` ADD `apellido_paterno` VARCHAR(150) NULL DEFAULT NULL AFTER `apellidos`";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `administrador` ADD `apellido_materno` VARCHAR(150) NULL DEFAULT NULL AFTER `apellido_paterno`";
        $this->_db->query($sql);
        return true;
    }
}
