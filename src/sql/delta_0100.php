<?php

class Delta_0100 extends App_Migration_Delta
{
    protected $_autor = 'Dimas Gustavo';
    protected $_desc = 'cambio de nombre de beneficio de restauran a restaurante';
    
    public function up()
    {        
        $sql = "update tipo_beneficio set abreviado='Restaurante',slug='restaurante' where id=8;";
        $this->_db->query($sql);
        return true;
    }
}