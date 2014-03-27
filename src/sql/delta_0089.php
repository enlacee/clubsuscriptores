<?php

class Delta_0089 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Actualización de campos tabla beneficio, segun el ticket #20199';
    
    public function up()
    {   
        $sql = "ALTER TABLE `beneficio` CHANGE `terminos_condiciones_web` `terminos_condiciones_web` varchar(2000) NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` CHANGE `terminos_condiciones_cupon` `terminos_condiciones_cupon` varchar(1500) NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` CHANGE `descripcion_cupon` `descripcion_cupon` varchar(1000) NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` CHANGE `descripcion` `descripcion` varchar(500) NULL;";
        $this->_db->query($sql);
        return true;
    }
}
