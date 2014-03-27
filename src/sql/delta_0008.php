<?php

class Delta_0008 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregando el campo sin_limite_por_suscriptor , descripcion_corta y modificando el tamanio del campo path_logo en la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `sin_limite_por_suscriptor` TINYINT NOT NULL DEFAULT '0' AFTER `maximo_por_subscriptor`";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` ADD `descripcion_corta` VARCHAR( 120 ) NULL AFTER `descripcion`";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` ADD `publicado` TINYINT NOT NULL DEFAULT '0' AFTER `activo`";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` CHANGE `path_logo` `path_logo` VARCHAR( 100 ) NULL DEFAULT NULL";
        $this->_db->query($sql);
        return true;
    }
}
