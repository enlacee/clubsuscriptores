<?php

class Delta_0083 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregar campo descripcion_cupon a la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `descripcion_cupon` VARCHAR(300) NULL;";
        $this->_db->query($sql);        
        return true;
    }
}
