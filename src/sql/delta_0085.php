<?php

class Delta_0085 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregar campo pdf_info_file y pdf_info_descrip a la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `pdf_info_file` varchar(100) NULL;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` ADD `pdf_info_descrip` varchar(100) NULL;";
        $this->_db->query($sql);
        return true;
    }
}
