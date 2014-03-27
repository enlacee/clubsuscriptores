<?php

/**

 * Description of delta_0070
 *
 * @author FCJ
 */
class Delta_0070 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campo stock_actual en la tabla detalle_tipo_promocion';
    
    public function up()
    {
        $sql = "ALTER TABLE detalle_tipo_promocion ADD stock_actual INT(11) NOT NULL DEFAULT 0;";
        $this->_db->query($sql);
        return true;
    }
}