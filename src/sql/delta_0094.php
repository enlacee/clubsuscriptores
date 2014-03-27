<?php

class Delta_0094 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregando campo fecha_inicio_publicacion y fecha_fin_publicacion en la tabla ';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio_version` ADD `fecha_inicio_publicacion` DATETIME NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio_version` ADD `fecha_fin_publicacion` DATETIME NULL AFTER fecha_inicio_publicacion;";
        $this->_db->query($sql);
        $sql = "update `beneficio_version` set fecha_inicio_publicacion=fecha_inicio_vigencia;";
        $this->_db->query($sql);
        $sql = "update `beneficio_version` set fecha_fin_publicacion=fecha_fin_vigencia;";
        $this->_db->query($sql);
        return true;
    }
}