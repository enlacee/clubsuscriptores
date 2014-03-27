<?php

class Delta_0082 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Aumentar el tamaño del campo como tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` CHANGE `como` `como` VARCHAR(150) NULL DEFAULT NULL;";
        $this->_db->query($sql);        
        return true;
    }
}
