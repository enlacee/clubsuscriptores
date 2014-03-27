<?php

/**
 * Description of delta_0031
 *
 * @author Favio
 */
class Delta_0031 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Cambiando valor por defecto a NULO';
    
    public function up()
    {
        $sql = "ALTER TABLE  `beneficio` CHANGE  `chapita_color`  `chapita_color` VARCHAR( 20 ) 
            CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
        $this->_db->query($sql);
        return true;
    }
}
