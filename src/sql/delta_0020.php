<?php

/**
 * Description of delta_0020
 *
 * @author DjTabo
 */
class Delta_0020 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Seteando valores de campo estado a la tabla cupon';
    
    public function up()
    {
        $sql = "ALTER TABLE `cupon` CHANGE `estado` `estado` enum('generado',".
            "'redimido','conciliado') DEFAULT NULL";
        $this->_db->query($sql);
        return true;
    }
}
