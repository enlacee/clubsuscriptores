<?php

class Delta_0077 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregar nuevo campo fecha_inicio_evento en la tabla articulo';
    
    public function up()
    {
        $sql = "ALTER TABLE `articulo` ADD `fecha_inicio_evento` datetime not NULL;";
        $this->_db->query($sql);
        return true;
    }

}
