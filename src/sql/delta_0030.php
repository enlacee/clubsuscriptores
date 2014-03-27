<?php

/**
 * Description of delta_0030
 *
 * @author FCJ
 */
class Delta_0030 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Cambio de valores del campo enum tipo_documento en suscriptor y administrador';
    
    public function up()
    {
        $sql = "ALTER TABLE  `suscriptor`
            CHANGE  `tipo_documento`  `tipo_documento` ENUM('DNI', 'CEX') 
            CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
        $this->_db->query($sql);
        $sql = "ALTER TABLE  `administrador`
            CHANGE  `tipo_documento`  `tipo_documento` ENUM('DNI', 'CEX' ) 
            CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
        $this->_db->query($sql);
        return true;
    }
}
