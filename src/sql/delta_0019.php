<?php

/**
 * Description of delta_0019
 *
 * @author DjTabo
 */
class Delta_0019 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregar campo generar_cupon a la tabla beneficio';
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `generar_cupon` TINYINT(4) NOT NULL DEFAULT 0 AFTER `publicado`";
        $this->_db->query($sql);
        return true;
    }

}
