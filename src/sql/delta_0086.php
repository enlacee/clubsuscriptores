<?php

class Delta_0086 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregar campo terminos_condiciones_web,terminos_condiciones_cupon
        a la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `terminos_condiciones_web` varchar(1000) NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` ADD `terminos_condiciones_cupon` varchar(500) NULL;";
        $this->_db->query($sql);
        return true;
    }
}
