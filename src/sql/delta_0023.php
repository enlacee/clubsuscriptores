<?php

/**
 * Description of delta_0017
 *
 * @author FCJ
 */
class Delta_0023 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campos de informacion de fechas de estado de publicacion de beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE  `beneficio` ADD  `fecha_actualizacion` DATETIME NULL AFTER  `fecha_registro` ,
                ADD  `fecha_publicacion` DATETIME NULL AFTER  `fecha_actualizacion` ,
                ADD  `fecha_retiro` DATETIME NULL AFTER  `fecha_publicacion`";
        $this->_db->query($sql);
        $sql = "ALTER TABLE  `beneficio` ADD  `publicado_por` INT( 11 ) NULL AFTER  `actualizado_por` ,
                ADD  `retirado_por` INT( 11 ) NULL AFTER  `publicado_por`";
        $this->_db->query($sql);
        $sql = "ALTER TABLE  `beneficio_version` ADD  `creado_por` INT( 11 ) NOT NULL AFTER  `beneficio_id`";
        $this->_db->query($sql);
        return true;
    }
}
