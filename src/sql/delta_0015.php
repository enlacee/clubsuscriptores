<?php

/**
 * Description of delta_0015
 *
 * @author DjTabo
 */
class Delta_0015 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregar campo nrorespuesta a la tabla opcion_encuesta';
    
    public function up()
    {
        $sql = "ALTER TABLE `opcion_encuesta` ADD `nrorespuesta` int(11) NULL AFTER opcion";
        $this->_db->query($sql);
        return true;
    }
}
