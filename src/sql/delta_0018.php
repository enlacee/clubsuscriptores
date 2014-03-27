<?php

/**
 * Description of delta_0017
 *
 * @author JFlorian
 */
class Delta_0018 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campo informacion_adicional a la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `informacion_adicional` TEXT NULL AFTER `telefono_info`";
        $this->_db->query($sql);
        return true;
    }
}
