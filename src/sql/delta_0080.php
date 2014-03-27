<?php

class Delta_0080 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregar campo tipo de moneda a la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `tipo_moneda_id` TINYINT NOT NULL DEFAULT 1;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE beneficio ADD FOREIGN KEY(tipo_moneda_id) REFERENCES tipo_moneda(id);";
        $this->_db->query($sql);
        return true;
    }
}
