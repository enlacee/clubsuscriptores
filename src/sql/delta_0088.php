<?php

class Delta_0088 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Cambiar campo maximo_por_suscriptor a 4 digitos tabla detalle_tipo_promocion';
    
    public function up()
    {
        $sql = "ALTER TABLE `detalle_tipo_promocion` CHANGE `maximo_por_suscriptor` `maximo_por_suscriptor`".
                " int(4) NULL DEFAULT '0';";
        $this->_db->query($sql);
        return true;
    }
}