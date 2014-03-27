<?php

class Delta_0096 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
        protected $_desc = 'Cambiar campo sin_stock cambiando valores 0 1 2 en la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` CHANGE `sin_stock` `sin_stock`".
                " enum('1','0','2' ) DEFAULT '0'".
                " COMMENT 'este campo sera para stock: ilimitado, general y por detalle ';";
        $this->_db->query($sql);
        $sql = "UPDATE beneficio SET sin_stock='2' WHERE sin_stock!= '1' AND id IN (SELECT beneficio_id FROM detalle_tipo_promocion ".
                "WHERE cantidad >0  GROUP BY beneficio_id);";
        $this->_db->query($sql);
        $sql = "ALTER TABLE detalle_tipo_promocion DROP COLUMN tipo_moneda_id;";
        $this->_db->query($sql);
        return true;
    }
}