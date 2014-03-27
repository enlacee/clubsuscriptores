<?php

/**
 * Description of delta_0024
 *
 * @author DjTabo
 */
class Delta_0024 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregar nuevos campos a la tabla parametro';
    
    public function up()
    {
        $sql = "ALTER TABLE `parametro` ADD `tipo` VARCHAR(50) NOT NULL AFTER `valor` ,
                ADD `categoria` VARCHAR(50) NULL AFTER `tipo` ,
                ADD `es_editable` TINYINT(4) NULL AFTER `categoria` ,
                ADD `archivo` VARCHAR(20) NOT NULL AFTER `es_editable` ,
                ADD `actualizar` TINYINT(4) NOT NULL DEFAULT 1 AFTER `archivo`";
        $this->_db->query($sql);
        return true;
    }
}
