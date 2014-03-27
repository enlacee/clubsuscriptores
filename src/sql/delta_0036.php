<?php

/**
 * Description of delta_0036
 *
 * @author FCJ
 */
class Delta_0036 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campo codigo para identificar beneficios';

    public function up()
    {
        $sql = "ALTER TABLE  `beneficio` ADD  `codigo` VARCHAR( 12 ) NULL DEFAULT NULL AFTER  `veces_visto`, ADD UNIQUE (`codigo`)";
        $this->_db->query($sql);
        return true;
    }

}
