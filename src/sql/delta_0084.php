<?php

class Delta_0084 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregar campo tipo de moneda a la tabla detalle_tipo_promocion';
    
    public function up()
    {
        $sql = "ALTER TABLE `detalle_tipo_promocion` ADD `maximo_por_suscriptor` TINYINT NULL DEFAULT 0;";
        $this->_db->query($sql);
        return true;
    }
}
