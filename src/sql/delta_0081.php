<?php

class Delta_0081 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregar campo es_destacado_banner a la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `es_destacado_banner` TINYINT NOT NULL DEFAULT 0;";
        $this->_db->query($sql);        
        return true;
    }
}
