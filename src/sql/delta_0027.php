<?php

/**
 * Description of delta_0027
 *
 * @author DjTabo
 */
class Delta_0027 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Cambiar tamaño de campo valor en la tabla parametro';
    
    public function up()
    {
        $sql = "ALTER TABLE `parametro` CHANGE `valor` `valor` TEXT NULL";
        $this->_db->query($sql);
        return true;
    }
}
