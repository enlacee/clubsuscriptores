<?php

/**
 * Description of delta_0056
 *
 * @author FCJ
 */
class Delta_0056 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campo iframeH en la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `iframeH` INT(11) NULL DEFAULT NULL";
        $this->_db->query($sql);
        return true;
    }
}
