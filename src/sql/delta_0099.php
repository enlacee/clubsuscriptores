<?php

class Delta_0099 extends App_Migration_Delta
{
    protected $_autor = 'Dimas Gustavo';
    protected $_desc = 'cambiar el nombres de beneficios y creación de nuevos beneficios';
    
    public function up()
    {
        $sql = "update tipo_beneficio set nombre='Productos y Servicios',abreviado='P. Servicios',slug='productos-servicios' where id=2;";
        $this->_db->query($sql);
        $sql = "update tipo_beneficio set nombre='Ventas Exclusivas',abreviado='V. Exclusiva',slug='ventas-exclusivas' where id=3;";
        $this->_db->query($sql);
        $sql = "insert into tipo_beneficio(nombre,abreviado,slug,activo) values('Restaurantes','Restauran','restauran',1);";
        $this->_db->query($sql);
        $sql = "insert into tipo_beneficio(nombre,abreviado,slug,activo) values('Educación','Educación','educacion',1);";
        $this->_db->query($sql);
        return true;
    }
}